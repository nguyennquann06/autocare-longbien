<?php

namespace App\Services;

use App\Contracts\LlmProvider;
use RuntimeException;

class AiProviderManager
{
    public function __construct(
        private GeminiProvider
            $geminiProvider
    ) {
    }


    /**
     * Lấy provider được cấu hình
     * trong config/ai.php.
     */
    public function provider(): LlmProvider
    {
        $provider =
            strtolower(
                trim(
                    (string)
                    config(
                        'ai.provider',
                        'gemini'
                    )
                )
            );


        return match ($provider) {
            'gemini' =>
                $this->geminiProvider,

            default =>
                throw new RuntimeException(
                    "AI provider [{$provider}] chưa được hỗ trợ."
                ),
        };
    }


    public function name(): string
    {
        return $this
            ->provider()
            ->name();
    }


    public function isConfigured(): bool
    {
        return $this
            ->provider()
            ->isConfigured();
    }
}