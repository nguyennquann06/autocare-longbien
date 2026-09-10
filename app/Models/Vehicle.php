<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Vehicle extends Model
{
    use HasFactory;

    /**
     * Các thuộc tính được phép gán hàng loạt.
     */
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

    /**
     * Ép kiểu dữ liệu.
     */
    protected function casts(): array
    {
        return [
            'manufacture_year' => 'integer',
            'current_mileage' => 'integer',
        ];
    }

    /**
     * Xe thuộc một khách hàng.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Xe thuộc một hãng xe.
     */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(VehicleBrand::class, 'brand_id');
    }

    /**
     * Xe thuộc một dòng xe.
     */
    public function vehicleModel(): BelongsTo
    {
        return $this->belongsTo(VehicleModel::class, 'model_id');
    }
}