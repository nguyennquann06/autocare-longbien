<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ServiceCategory extends Model
{
    use HasFactory;

    /**
     * Các trường được phép gán dữ liệu hàng loạt.
     */
    protected $fillable = [
        'name',
        'code',
        'description',
        'is_active',
    ];

    /**
     * Ép kiểu dữ liệu.
     */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    /**
     * Một nhóm dịch vụ có nhiều dịch vụ.
     */
    public function services(): HasMany
    {
        return $this->hasMany(
            Service::class,
            'category_id'
        );
    }
}