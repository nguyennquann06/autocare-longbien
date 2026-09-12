<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatMessage extends Model
{
    use HasFactory;

    public const ROLE_USER = 'USER';

    public const ROLE_ASSISTANT = 'ASSISTANT';

    public const ROLE_SYSTEM = 'SYSTEM';

    protected $fillable = [
        'conversation_id',
        'role',
        'content',
        'metadata',
        'token_count',
    ];

    protected function casts(): array
    {
        return [
            'metadata' => 'array',
            'token_count' => 'integer',
        ];
    }

    public function conversation(): BelongsTo
    {
        return $this->belongsTo(
            ChatConversation::class,
            'conversation_id'
        );
    }
}