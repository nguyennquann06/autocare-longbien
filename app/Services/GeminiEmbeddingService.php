<?php

namespace App\Services;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use RuntimeException;
use Throwable;

class GeminiEmbeddingService
{
    /**
     * Embedding cho QUERY tìm kiếm.
     *
     * Gemini Embedding 2 khuyến nghị
     * asymmetric retrieval format:
     *
     * task: search result | query: ...
     *
     * @return array<int, float>
     */
    public function embedQuery(
        string $query
    ): array {
        $query = trim($query);

        if ($query === '') {
            throw new RuntimeException(
                'Không thể tạo embedding cho query rỗng.'
            );
        }

        $preparedText =
            'task: search result | query: '
            . $query;

        return $this->embedText(
            $preparedText
        );
    }


    /**
     * Embedding cho DOCUMENT trong
     * Knowledge Base.
     *
     * Format:
     *
     * title: ... | text: ...
     *
     * @return array<int, float>
     */
    public function embedDocument(
        string $title,
        string $content
    ): array {
        $title =
            trim($title);

        $content =
            trim($content);

        if ($content === '') {
            throw new RuntimeException(
                'Không thể tạo embedding cho document rỗng.'
            );
        }

        if ($title === '') {
            $title = 'none';
        }

        $preparedText =
            'title: '
            . $title
            . ' | text: '
            . $content;

        return $this->embedText(
            $preparedText
        );
    }


    /**
     * Giữ method embed() để không làm
     * hỏng code cũ nếu có chỗ đang gọi.
     *
     * Mặc định coi input là query.
     *
     * @return array<int, float>
     */
    public function embed(
        string $text
    ): array {
        return $this->embedQuery(
            $text
        );
    }


    /**
     * Gọi Gemini Embedding API.
     *
     * @return array<int, float>
     */
    private function embedText(
        string $text
    ): array {
        if (!$this->isConfigured()) {
            throw new RuntimeException(
                'Gemini Embedding chưa được cấu hình. '
                . 'Hãy kiểm tra GEMINI_API_KEY.'
            );
        }

        $apiKey =
            (string) config(
                'ai.providers.gemini.api_key'
            );

        $baseUrl =
            rtrim(
                (string) config(
                    'ai.providers.gemini.base_url',
                    'https://generativelanguage.googleapis.com/v1beta'
                ),
                '/'
            );

        $model =
            $this->normalizeModelName(
                (string) config(
                    'ai.embedding.model',
                    'gemini-embedding-2'
                )
            );

        $dimensions =
            (int) config(
                'ai.embedding.dimensions',
                768
            );

        $timeout =
            (int) config(
                'ai.embedding.timeout',
                45
            );

        $connectTimeout =
            (int) config(
                'ai.embedding.connect_timeout',
                10
            );

        $maxAttempts =
            max(
                1,
                (int) config(
                    'ai.embedding.max_attempts',
                    3
                )
            );

        $endpoint =
            $baseUrl
            . '/models/'
            . $model
            . ':embedContent';

        $payload = [
            'model' =>
                'models/' . $model,

            'content' => [
                'parts' => [
                    [
                        'text' =>
                            $text,
                    ],
                ],
            ],

            'output_dimensionality' =>
                $dimensions,
        ];

        $lastError = null;

        for (
            $attempt = 1;
            $attempt <= $maxAttempts;
            $attempt++
        ) {
            try {
                $response =
                    Http::acceptJson()
                        ->asJson()
                        ->withHeaders([
                            'x-goog-api-key' =>
                                $apiKey,
                        ])
                        ->connectTimeout(
                            $connectTimeout
                        )
                        ->timeout(
                            $timeout
                        )
                        ->post(
                            $endpoint,
                            $payload
                        );

                if ($response->successful()) {
                    $vector =
                        $this->extractEmbedding(
                            $response->json()
                        );

                    if (empty($vector)) {
                        throw new RuntimeException(
                            'Gemini Embedding không trả về vector.'
                        );
                    }

                    if (
                        count($vector)
                        !== $dimensions
                    ) {
                        throw new RuntimeException(
                            'Embedding trả về '
                            . count($vector)
                            . ' chiều, trong khi hệ thống yêu cầu '
                            . $dimensions
                            . ' chiều.'
                        );
                    }

                    return $vector;
                }

                $status =
                    $response->status();

                $apiMessage =
                    data_get(
                        $response->json(),
                        'error.message'
                    );

                $lastError =
                    "Gemini Embedding HTTP {$status}"
                    . (
                        $apiMessage
                            ? ": {$apiMessage}"
                            : '.'
                    );

                $shouldRetry =
                    $status === 429
                    ||
                    $status >= 500;

                if (
                    !$shouldRetry
                    ||
                    $attempt >= $maxAttempts
                ) {
                    throw new RuntimeException(
                        $lastError
                    );
                }

                sleep($attempt);
            } catch (
                ConnectionException $exception
            ) {
                $lastError =
                    'Không thể kết nối tới Gemini Embedding API.';

                if (
                    $attempt >= $maxAttempts
                ) {
                    throw new RuntimeException(
                        $lastError,
                        previous: $exception
                    );
                }

                sleep($attempt);
            } catch (
                RuntimeException $exception
            ) {
                throw $exception;
            } catch (
                Throwable $exception
            ) {
                throw new RuntimeException(
                    'Đã xảy ra lỗi khi tạo Gemini embedding.',
                    previous: $exception
                );
            }
        }

        throw new RuntimeException(
            $lastError
            ?? 'Không thể tạo embedding.'
        );
    }


    public function isConfigured(): bool
    {
        return filled(
            config(
                'ai.providers.gemini.api_key'
            )
        )
            &&
            filled(
                config(
                    'ai.embedding.model'
                )
            );
    }


    /**
     * @return array<int, float>
     */
    private function extractEmbedding(
        array $data
    ): array {
        $values =
            data_get(
                $data,
                'embedding.values'
            );

        if (!is_array($values)) {
            $values =
                data_get(
                    $data,
                    'embeddings.0.values'
                );
        }

        if (!is_array($values)) {
            return [];
        }

        return array_map(
            static fn ($value): float =>
                (float) $value,
            $values
        );
    }


    private function normalizeModelName(
        string $model
    ): string {
        $model =
            trim($model);

        if (
            str_starts_with(
                $model,
                'models/'
            )
        ) {
            return substr(
                $model,
                strlen('models/')
            );
        }

        return $model;
    }
}