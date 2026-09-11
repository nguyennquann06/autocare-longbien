<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'appointment_code',
        'customer_id',
        'vehicle_id',
        'contact_name',
        'contact_phone',
        'contact_email',
        'appointment_date',
        'appointment_time',
        'estimated_total',
        'estimated_duration_minutes',
        'status',
        'customer_note',
        'staff_note',
    ];

    protected function casts(): array
    {
        return [
            'appointment_date' => 'date',

            'estimated_total' => 'decimal:2',

            'estimated_duration_minutes' =>
                'integer',
        ];
    }

    /**
     * Lịch hẹn thuộc khách hàng.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(
            Customer::class,
            'customer_id'
        );
    }

    /**
     * Lịch hẹn thuộc phương tiện.
     */
    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(
            Vehicle::class,
            'vehicle_id'
        );
    }

    /**
     * Các dịch vụ khách đã chọn
     * khi đặt lịch.
     */
    public function services(): BelongsToMany
    {
        return $this->belongsToMany(
            Service::class,
            'appointment_service',
            'appointment_id',
            'service_id'
        )
            ->withPivot([
                'price',
                'estimated_duration_minutes',
            ])
            ->withTimestamps();
    }

    /**
     * Một lịch hẹn có tối đa
     * một phiếu bảo dưỡng.
     */
    public function serviceOrder(): HasOne
    {
        return $this->hasOne(
            ServiceOrder::class,
            'appointment_id'
        );
    }
}