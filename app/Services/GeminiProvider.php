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


        /*
         * Không cần sửa config/ai.php.
         *
         * Nếu chưa khai báo các key này,
         * hệ thống sử dụng default:
         *
         * max_attempts = 2
         * base delay = 800 ms
         * max acceptable Retry-After = 3 giây
         */
        $maxAttempts =
            max(
                1,
                (int) config(
                    'ai.providers.gemini.max_attempts',
                    2
                )
            );


        $retryBaseDelayMs =
            max(
                100,
                (int) config(
                    'ai.providers.gemini.retry_base_delay_ms',
                    800
                )
            );


        $maxRetryDelaySeconds =
            max(
                0,
                (float) config(
                    'ai.providers.gemini.retry_max_delay_seconds',
                    3
                )
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


        $response = null;

        $attempt = 0;


        while ($attempt < $maxAttempts) {
            $attempt++;


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
            } catch (
                ConnectionException $exception
            ) {
                /*
                 * Mất kết nối / timeout:
                 * retry ngắn nếu còn lượt.
                 */
                if ($attempt < $maxAttempts) {
                    $this->sleepMilliseconds(
                        min(
                            (int)
                            (
                                $retryBaseDelayMs
                                * $attempt
                            ),
                            2500
                        )
                    );

                    continue;
                }


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


            /*
             * Thành công:
             * thoát retry loop.
             */
            if ($response->successful()) {
                break;
            }


            /*
             * Chỉ retry với lỗi tạm thời:
             *
             * - 429
             * - 5xx
             *
             * Nhưng 429 yêu cầu chờ quá lâu
             * thì fallback ngay thay vì giữ
             * request web treo hàng chục giây.
             */
            if (
                $this->shouldRetry(
                    $response,
                    $attempt,
                    $maxAttempts,
                    $maxRetryDelaySeconds
                )
            ) {
                $delayMs =
                    $this
                        ->calculateRetryDelayMilliseconds(
                            $response,
                            $attempt,
                            $retryBaseDelayMs,
                            $maxRetryDelaySeconds
                        );


                $this->sleepMilliseconds(
                    $delayMs
                );


                continue;
            }


            /*
             * Không nên retry hoặc đã hết lượt.
             */
            $this->ensureSuccessfulResponse(
                $response
            );
        }


        if (
            !$response
            ||
            !$response->successful()
        ) {
            if ($response) {
                $this->ensureSuccessfulResponse(
                    $response
                );
            }


            throw new RuntimeException(
                'Gemini API không trả về phản hồi hợp lệ.'
            );
        }


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

                'attempts' =>
                    $attempt,
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
     * Có nên retry response này không?
     */
    private function shouldRetry(
        Response $response,
        int $attempt,
        int $maxAttempts,
        float $maxRetryDelaySeconds
    ): bool {
        if ($attempt >= $maxAttempts) {
            return false;
        }


        $status =
            $response->status();


        if (
            $status !== 429
            &&
            $status < 500
        ) {
            return false;
        }


        /*
         * Với 429, nếu Google nói:
         *
         * "retry in 23s"
         *
         * thì không bắt người dùng chờ.
         * ChatService sẽ fallback ngay.
         */
        if ($status === 429) {
            $suggestedDelay =
                $this
                    ->extractSuggestedRetryDelaySeconds(
                        $response
                    );


            if (
                $suggestedDelay !== null
                &&
                $suggestedDelay
                >
                $maxRetryDelaySeconds
            ) {
                return false;
            }
        }


        return true;
    }


    /**
     * Tính thời gian retry.
     */
    private function calculateRetryDelayMilliseconds(
        Response $response,
        int $attempt,
        int $baseDelayMs,
        float $maxRetryDelaySeconds
    ): int {
        $suggestedDelay =
            $this
                ->extractSuggestedRetryDelaySeconds(
                    $response
                );


        if (
            $suggestedDelay !== null
            &&
            $suggestedDelay >= 0
            &&
            $suggestedDelay
            <=
            $maxRetryDelaySeconds
        ) {
            return max(
                100,
                (int)
                round(
                    $suggestedDelay
                    * 1000
                )
            );
        }


        /*
         * Exponential-ish backoff:
         *
         * attempt 1 -> 800 ms
         * attempt 2 -> 1600 ms
         */
        $delay =
            $baseDelayMs
            *
            max(
                1,
                $attempt
            );


        $maxDelayMs =
            max(
                500,
                (int)
                round(
                    $maxRetryDelaySeconds
                    * 1000
                )
            );


        return min(
            $delay,
            $maxDelayMs
        );
    }


    /**
     * Đọc Retry-After từ:
     *
     * - HTTP header
     * - error.details.retryDelay
     * - message "Please retry in 23.1s"
     */
    private function extractSuggestedRetryDelaySeconds(
        Response $response
    ): ?float {
        $retryAfter =
            $response->header(
                'Retry-After'
            );


        if (
            is_string($retryAfter)
            &&
            is_numeric(
                trim($retryAfter)
            )
        ) {
            return max(
                0,
                (float)
                trim($retryAfter)
            );
        }


        $data =
            $response->json();


        $details =
            data_get(
                $data,
                'error.details',
                []
            );


        if (is_array($details)) {
            foreach ($details as $detail) {
                if (!is_array($detail)) {
                    continue;
                }


                $retryDelay =
                    $detail[
                        'retryDelay'
                    ]
                    ?? null;


                if (
                    is_string($retryDelay)
                    &&
                    preg_match(
                        '/([0-9]+(?:\.[0-9]+)?)s/i',
                        $retryDelay,
                        $matches
                    )
                ) {
                    return
                        (float)
                        $matches[1];
                }
            }
        }


        $apiMessage =
            (string)
            data_get(
                $data,
                'error.message',
                ''
            );


        if (
            preg_match(
                '/retry\s+in\s+([0-9]+(?:\.[0-9]+)?)s/i',
                $apiMessage,
                $matches
            )
        ) {
            return
                (float)
                $matches[1];
        }


        return null;
    }


    /**
     * Sleep bằng millisecond.
     */
    private function sleepMilliseconds(
        int $milliseconds
    ): void {
        if ($milliseconds <= 0) {
            return;
        }


        usleep(
            $milliseconds
            * 1000
        );
    }


    /**
     * Kiểm tra HTTP error.
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