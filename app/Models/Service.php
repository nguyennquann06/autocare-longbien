<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    use HasFactory;

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
     * Dịch vụ thuộc nhóm dịch vụ.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(
            ServiceCategory::class,
            'category_id'
        );
    }

    /**
     * Các lịch hẹn mà khách
     * đã chọn dịch vụ này.
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

    /**
     * Các hạng mục thực tế trong
     * phiếu bảo dưỡng sử dụng dịch vụ này.
     */
    public function serviceOrderItems(): HasMany
    {
        return $this->hasMany(
            ServiceOrderItem::class,
            'service_id'
        );
    }
}