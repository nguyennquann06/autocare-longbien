<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'brand_id',
        'model_id',
        'license_plate',
        'vin',
        'manufacture_year',
        'color',
        'fuel_type',
        'current_mileage',
        'note',
    ];

    protected function casts(): array
    {
        return [
            'manufacture_year' => 'integer',

            'current_mileage' => 'integer',
        ];
    }

    /**
     * Xe thuộc khách hàng.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(
            Customer::class,
            'customer_id'
        );
    }

    /**
     * Xe thuộc hãng xe.
     */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(
            VehicleBrand::class,
            'brand_id'
        );
    }

    /**
     * Xe thuộc dòng xe.
     */
    public function vehicleModel(): BelongsTo
    {
        return $this->belongsTo(
            VehicleModel::class,
            'model_id'
        );
    }

    /**
     * Một xe có nhiều lịch hẹn.
     */
    public function appointments(): HasMany
    {
        return $this->hasMany(
            Appointment::class,
            'vehicle_id'
        );
    }

    /**
     * Một xe có nhiều phiếu bảo dưỡng.
     *
     * Đây chính là cơ sở để sau này
     * tạo lịch sử bảo dưỡng của xe.
     */
    public function serviceOrders(): HasMany
    {
        return $this->hasMany(
            ServiceOrder::class,
            'vehicle_id'
        );
    }
}