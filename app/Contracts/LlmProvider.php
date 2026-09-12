<?php

namespace App\Contracts;

use App\Data\LlmResponse;

interface LlmProvider
{
    /**
     * Gửi prompt tới LLM.
     *
     * @param array<int, array{
     *     role: string,
     *     content: string
     * }> $messages
     */
    public function chat(
        array $messages,
        array $options = []
    ): LlmResponse;

    /**
     * Tên provider hiện tại.
     */
    public function name(): string;

    /**
     * Kiểm tra provider đã được cấu hình
     * đủ để gửi request hay chưa.
     */
    public function isConfigured(): bool;
}