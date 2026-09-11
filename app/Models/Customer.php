<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'full_name',
        'phone',
        'email',
        'date_of_birth',
        'gender',
        'address',
        'note',
    ];

    protected function casts(): array
    {
        return [
            'date_of_birth' =>
                'date',
        ];
    }

    /**
     * Hồ sơ khách hàng thuộc tài khoản.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'user_id'
        );
    }

    /**
     * Xe của khách hàng.
     */
    public function vehicles(): HasMany
    {
        return $this->hasMany(
            Vehicle::class,
            'customer_id'
        );
    }

    /**
     * Lịch hẹn của khách hàng.
     */
    public function appointments(): HasMany
    {
        return $this->hasMany(
            Appointment::class,
            'customer_id'
        );
    }

    /**
     * Phiếu bảo dưỡng của khách hàng.
     */
    public function serviceOrders(): HasMany
    {
        return $this->hasMany(
            ServiceOrder::class,
            'customer_id'
        );
    }

    /**
     * Các hóa đơn của khách hàng.
     */
    public function invoices(): HasMany
    {
        return $this->hasMany(
            Invoice::class,
            'customer_id'
        );
    }
}