<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VehicleBrand extends Model
{
    use HasFactory;

    /**
     * Các thuộc tính được phép gán hàng loạt.
     */
    protected $fillable = [
        'name',
        'country',
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
     * Một hãng xe có nhiều dòng xe.
     */
    public function models(): HasMany
    {
        return $this->hasMany(VehicleModel::class, 'brand_id');
    }

    /**
     * Một hãng xe có thể có nhiều phương tiện.
     */
    public function vehicles(): HasMany
    {
        return $this->hasMany(Vehicle::class, 'brand_id');
    }
}