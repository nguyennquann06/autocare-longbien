<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Service extends Model
{
    use HasFactory;

    /**
     * Các trường được phép gán dữ liệu hàng loạt.
     */
    protected $fillable = [
        'category_id',
        'name',
        'code',
        'description',
        'base_price',
        'estimated_duration_minutes',
        'mileage_interval',
        'month_interval',
        'is_active',
    ];

    /**
     * Ép kiểu dữ liệu.
     */
    protected function casts(): array
    {
        return [
            'base_price' => 'decimal:2',

            'estimated_duration_minutes' =>
                'integer',

            'mileage_interval' =>
                'integer',

            'month_interval' =>
                'integer',

            'is_active' =>
                'boolean',
        ];
    }

    /**
     * Dịch vụ thuộc một nhóm dịch vụ.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(
            ServiceCategory::class,
            'category_id'
        );
    }

    /**
     * Một dịch vụ có thể xuất hiện
     * trong nhiều lịch hẹn.
     */
    public function appointments(): BelongsToMany
    {
        return $this->belongsToMany(
            Appointment::class,
            'appointment_service',
            'service_id',
            'appointment_id'
        )
            ->withPivot([
                'price',
                'estimated_duration_minutes',
            ])
            ->withTimestamps();
    }
}