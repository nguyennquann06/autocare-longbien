<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    /**
     * Các trường được phép gán hàng loạt.
     */
    protected $fillable = [
        'user_id',
        'role_code',
        'action',
        'entity_type',
        'entity_id',
        'description',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
    ];


    /**
     * Cast dữ liệu.
     */
    protected function casts(): array
    {
        return [
            'old_values' =>
                'array',

            'new_values' =>
                'array',
        ];
    }


    /**
     * Người thực hiện hành động.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'user_id'
        );
    }
}