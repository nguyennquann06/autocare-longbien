<?php

namespace App\Services;

use App\Contracts\LlmProvider;
use App\Data\LlmResponse;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use RuntimeException;
use Throwable;

class GeminiProvider implements LlmProvider
{
    public function name(): string
    {
        return 'gemini';
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
                    'ai.providers.gemini.model'
                )
            );
    }


    public function chat(
        array $messages,
        array $options = []
    ): LlmResponse {
        if (!$this->isConfigured()) {
            throw new RuntimeException(
                'Gemini API chưa được cấu hình. '
                . 'Vui lòng kiểm tra GEMINI_API_KEY '
                . 'và GEMINI_MODEL trong file .env.'
            );
        }


        $apiKey =
            (string) config(
                'ai.providers.gemini.api_key'
            );


        $model =
            $this->normalizeModelName(
                (string) config(
                    'ai.providers.gemini.model'
                )
            );


        $baseUrl =
            rtrim(
                (string) config(
                    'ai.providers.gemini.base_url'
                ),
                '/'
            );


        $timeout =
            (int) config(
                'ai.providers.gemini.timeout',
                45
            );


        $connectTimeout =
            (int) config(
                'ai.providers.gemini.connect_timeout',
                10
            );


        $endpoint =
            $baseUrl
            . '/models/'
            . $model
            . ':generateContent';


        $payload =
            $this->buildPayload(
                $messages,
                $options
            );


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
        } catch (ConnectionException $exception) {
            throw new RuntimeException(
                'Không thể kết nối tới Gemini API.',
                previous: $exception
            );
        } catch (Throwable $exception) {
            throw new RuntimeException(
                'Đã xảy ra lỗi khi gọi Gemini API.',
                previous: $exception
            );
        }


        $this->ensureSuccessfulResponse(
            $response
        );


        $data =
            $response->json();


        $content =
            $this->extractText(
                $data
            );


        if ($content === '') {
            $blockReason =
                data_get(
                    $data,
                    'promptFeedback.blockReason'
                );


            $finishReason =
                data_get(
                    $data,
                    'candidates.0.finishReason'
                );


            $details =
                collect([
                    $blockReason
                        ? "blockReason={$blockReason}"
                        : null,

                    $finishReason
                        ? "finishReason={$finishReason}"
                        : null,
                ])
                    ->filter()
                    ->implode(', ');


            throw new RuntimeException(
                'Gemini không trả về nội dung văn bản.'
                . (
                    $details !== ''
                        ? " ({$details})"
                        : ''
                )
            );
        }


        $inputTokens =
            data_get(
                $data,
                'usageMetadata.promptTokenCount'
            );


        $outputTokens =
            data_get(
                $data,
                'usageMetadata.candidatesTokenCount'
            );


        return new LlmResponse(
            content: $content,

            provider: $this->name(),

            model: $model,

            inputTokens:
                is_numeric($inputTokens)
                    ? (int) $inputTokens
                    : null,

            outputTokens:
                is_numeric($outputTokens)
                    ? (int) $outputTokens
                    : null,

            metadata: [
                'finish_reason' =>
                    data_get(
                        $data,
                        'candidates.0.finishReason'
                    ),

                'total_token_count' =>
                    data_get(
                        $data,
                        'usageMetadata.totalTokenCount'
                    ),

                'thoughts_token_count' =>
                    data_get(
                        $data,
                        'usageMetadata.thoughtsTokenCount'
                    ),
            ],
        );
    }


    /**
     * Chuyển format chung của AutoCare
     * sang request Gemini.
     */
    private function buildPayload(
        array $messages,
        array $options
    ): array {
        $systemParts = [];

        $contents = [];


        foreach ($messages as $message) {
            $role =
                strtolower(
                    trim(
                        (string)
                        (
                            $message['role']
                            ?? 'user'
                        )
                    )
                );


            $content =
                trim(
                    (string)
                    (
                        $message['content']
                        ?? ''
                    )
                );


            if ($content === '') {
                continue;
            }


            if ($role === 'system') {
                $systemParts[] =
                    $content;

                continue;
            }


            $geminiRole =
                in_array(
                    $role,
                    [
                        'assistant',
                        'model',
                    ],
                    true
                )
                    ? 'model'
                    : 'user';


            $contents[] = [
                'role' =>
                    $geminiRole,

                'parts' => [
                    [
                        'text' =>
                            $content,
                    ],
                ],
            ];
        }


        if (empty($contents)) {
            throw new RuntimeException(
                'Không có nội dung hợp lệ để gửi tới Gemini.'
            );
        }


        $payload = [
            'contents' =>
                $contents,

            'generationConfig' => [
                'maxOutputTokens' =>
                    (int)
                    (
                        $options[
                            'max_output_tokens'
                        ]
                        ??
                        config(
                            'ai.max_output_tokens',
                            1200
                        )
                    ),
            ],
        ];


        if (!empty($systemParts)) {
            $payload[
                'systemInstruction'
            ] = [
                'parts' => [
                    [
                        'text' =>
                            implode(
                                "\n\n",
                                $systemParts
                            ),
                    ],
                ],
            ];
        }


        /*
         * Chỉ gửi temperature khi caller
         * chủ động truyền vào options.
         *
         * Không ép temperature mặc định
         * cho Gemini 3.x.
         */
        if (
            array_key_exists(
                'temperature',
                $options
            )
        ) {
            $payload[
                'generationConfig'
            ][
                'temperature'
            ] =
                (float)
                $options[
                    'temperature'
                ];
        }


        return $payload;
    }


    /**
     * Kiểm tra HTTP error,
     * rate limit và API error.
     */
    private function ensureSuccessfulResponse(
        Response $response
    ): void {
        if ($response->successful()) {
            return;
        }


        $status =
            $response->status();


        $apiMessage =
            data_get(
                $response->json(),
                'error.message'
            );


        if ($status === 429) {
            throw new RuntimeException(
                'Gemini API đang giới hạn tần suất '
                . 'hoặc quota đã hết.'
                . (
                    $apiMessage
                        ? " {$apiMessage}"
                        : ''
                )
            );
        }


        if (
            $status === 401
            ||
            $status === 403
        ) {
            throw new RuntimeException(
                'Gemini API từ chối xác thực. '
                . 'Hãy kiểm tra GEMINI_API_KEY.'
                . (
                    $apiMessage
                        ? " {$apiMessage}"
                        : ''
                )
            );
        }


        if ($status === 404) {
            throw new RuntimeException(
                'Không tìm thấy Gemini model đã cấu hình. '
                . 'Hãy kiểm tra GEMINI_MODEL.'
                . (
                    $apiMessage
                        ? " {$apiMessage}"
                        : ''
                )
            );
        }


        if ($status >= 500) {
            throw new RuntimeException(
                'Gemini API đang gặp sự cố phía máy chủ.'
                . (
                    $apiMessage
                        ? " {$apiMessage}"
                        : ''
                )
            );
        }


        throw new RuntimeException(
            "Gemini API trả về HTTP {$status}."
            . (
                $apiMessage
                    ? " {$apiMessage}"
                    : ''
            )
        );
    }


    /**
     * Ghép tất cả text Part
     * của candidate đầu tiên.
     */
    private function extractText(
        array $data
    ): string {
        $parts =
            data_get(
                $data,
                'candidates.0.content.parts',
                []
            );


        if (!is_array($parts)) {
            return '';
        }


        return trim(
            collect($parts)
                ->pluck('text')
                ->filter(
                    fn ($text) =>
                        is_string($text)
                        &&
                        trim($text) !== ''
                )
                ->implode("\n")
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
            $model =
                substr(
                    $model,
                    strlen('models/')
                );
        }


        return $model;
    }
}