<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KnowledgeDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'source_type',
        'source_id',
        'embedding',
        'metadata',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'embedding' => 'array',
            'metadata' => 'array',
            'is_active' => 'boolean',
        ];
    }
}