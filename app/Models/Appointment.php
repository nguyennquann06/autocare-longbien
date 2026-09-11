<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Appointment extends Model
{
    use HasFactory;

    /**
     * Các trường được phép gán dữ liệu hàng loạt.
     */
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

    /**
     * Ép kiểu dữ liệu.
     */
    protected function casts(): array
    {
        return [
            'appointment_date' => 'date',

            'estimated_total' => 'decimal:2',

            'estimated_duration_minutes' => 'integer',
        ];
    }

    /**
     * Lịch hẹn thuộc một khách hàng.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(
            Customer::class,
            'customer_id'
        );
    }

    /**
     * Lịch hẹn thuộc một phương tiện.
     */
    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(
            Vehicle::class,
            'vehicle_id'
        );
    }

    /**
     * Một lịch hẹn có thể có nhiều dịch vụ.
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
}