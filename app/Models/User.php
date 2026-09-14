<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Các trường được phép gán hàng loạt.
     */
    protected $fillable = [
        'role_id',
        'name',
        'email',
        'email_verified_at',
        'google_id',
        'password',
    ];

    /**
     * Các trường ẩn khi serialize.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Ép kiểu dữ liệu.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' =>
                'datetime',

            'password' =>
                'hashed',
        ];
    }

    /**
     * Vai trò tài khoản.
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(
            Role::class,
            'role_id'
        );
    }

    /**
     * Hồ sơ CUSTOMER.
     */
    public function customer(): HasOne
    {
        return $this->hasOne(
            Customer::class,
            'user_id'
        );
    }

    /**
     * Phiếu bảo dưỡng do user tạo.
     */
    public function createdServiceOrders(): HasMany
    {
        return $this->hasMany(
            ServiceOrder::class,
            'created_by'
        );
    }

    /**
     * Phiếu bảo dưỡng được phân công
     * cho TECHNICIAN.
     */
    public function technicianServiceOrders(): HasMany
    {
        return $this->hasMany(
            ServiceOrder::class,
            'technician_id'
        );
    }

    /**
     * Các giao dịch kho do user thực hiện.
     */
    public function inventoryTransactions(): HasMany
    {
        return $this->hasMany(
            InventoryTransaction::class,
            'performed_by'
        );
    }

    /**
     * Các hóa đơn do STAFF / ADMIN lập.
     */
    public function createdInvoices(): HasMany
    {
        return $this->hasMany(
            Invoice::class,
            'created_by'
        );
    }
}