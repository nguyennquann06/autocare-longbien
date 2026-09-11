<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    use HasFactory;

    /**
     * Các trường được phép gán dữ liệu hàng loạt.
     */
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

    /**
     * Ép kiểu dữ liệu.
     */
    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
        ];
    }

    /**
     * Hồ sơ khách hàng có thể thuộc một tài khoản.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'user_id'
        );
    }

    /**
     * Một khách hàng có nhiều phương tiện.
     */
    public function vehicles(): HasMany
    {
        return $this->hasMany(
            Vehicle::class,
            'customer_id'
        );
    }

    /**
     * Một khách hàng có nhiều lịch hẹn.
     */
    public function appointments(): HasMany
    {
        return $this->hasMany(
            Appointment::class,
            'customer_id'
        );
    }
}