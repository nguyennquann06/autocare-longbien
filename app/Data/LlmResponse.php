<?php

namespace App\Data;

class LlmResponse
{
    public function __construct(
        public readonly string $content,
        public readonly string $provider,
        public readonly ?string $model = null,
        public readonly ?int $inputTokens = null,
        public readonly ?int $outputTokens = null,
        public readonly array $metadata = [],
    ) {
    }

    public function totalTokens(): ?int
    {
        if (
            $this->inputTokens === null
            &&
            $this->outputTokens === null
        ) {
            return null;
        }

        return
            ($this->inputTokens ?? 0)
            +
            ($this->outputTokens ?? 0);
    }

    public function toArray(): array
    {
        return [
            'content' =>
                $this->content,

            'provider' =>
                $this->provider,

            'model' =>
                $this->model,

            'input_tokens' =>
                $this->inputTokens,

            'output_tokens' =>
                $this->outputTokens,

            'total_tokens' =>
                $this->totalTokens(),

            'metadata' =>
                $this->metadata,
        ];
    }
}