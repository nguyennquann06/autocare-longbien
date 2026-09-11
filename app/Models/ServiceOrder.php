<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ServiceOrder extends Model
{
    use HasFactory;

    /**
     * Các trường được phép gán dữ liệu hàng loạt.
     */
    protected $fillable = [
        'order_code',
        'appointment_id',
        'customer_id',
        'vehicle_id',
        'created_by',
        'technician_id',
        'received_mileage',
        'status',
        'received_at',
        'started_at',
        'completed_at',
        'vehicle_condition',
        'diagnosis',
        'staff_note',
        'technician_note',
        'service_total',
        'parts_total',
        'total_amount',
    ];

    /**
     * Ép kiểu dữ liệu.
     */
    protected function casts(): array
    {
        return [
            'received_mileage' =>
                'integer',

            'received_at' =>
                'datetime',

            'started_at' =>
                'datetime',

            'completed_at' =>
                'datetime',

            'service_total' =>
                'decimal:2',

            'parts_total' =>
                'decimal:2',

            'total_amount' =>
                'decimal:2',
        ];
    }

    /**
     * Lịch hẹn nguồn.
     */
    public function appointment(): BelongsTo
    {
        return $this->belongsTo(
            Appointment::class,
            'appointment_id'
        );
    }

    /**
     * Khách hàng.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(
            Customer::class,
            'customer_id'
        );
    }

    /**
     * Phương tiện.
     */
    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(
            Vehicle::class,
            'vehicle_id'
        );
    }

    /**
     * Nhân viên tạo phiếu.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'created_by'
        );
    }

    /**
     * Kỹ thuật viên phụ trách.
     */
    public function technician(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'technician_id'
        );
    }

    /**
     * Các hạng mục dịch vụ thực tế.
     */
    public function items(): HasMany
    {
        return $this->hasMany(
            ServiceOrderItem::class,
            'service_order_id'
        );
    }

    /**
     * Các phụ tùng thực tế sử dụng.
     */
    public function parts(): HasMany
    {
        return $this->hasMany(
            ServiceOrderPart::class,
            'service_order_id'
        );
    }

    /**
     * Các giao dịch kho liên quan.
     */
    public function inventoryTransactions(): HasMany
    {
        return $this->hasMany(
            InventoryTransaction::class,
            'service_order_id'
        );
    }

    /**
     * Một phiếu bảo dưỡng chỉ có
     * tối đa một hóa đơn.
     */
    public function invoice(): HasOne
    {
        return $this->hasOne(
            Invoice::class,
            'service_order_id'
        );
    }
}