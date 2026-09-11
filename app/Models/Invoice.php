<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{
    use HasFactory;

    /**
     * Trạng thái thanh toán.
     */
    public const STATUS_UNPAID = 'UNPAID';

    public const STATUS_PAID = 'PAID';

    public const STATUS_CANCELLED = 'CANCELLED';

    /**
     * Phương thức thanh toán.
     */
    public const METHOD_CASH = 'CASH';

    public const METHOD_BANK_TRANSFER = 'BANK_TRANSFER';

    public const METHOD_CARD = 'CARD';

    /**
     * Các trường được phép gán hàng loạt.
     */
    protected $fillable = [
        'invoice_code',
        'service_order_id',
        'customer_id',
        'created_by',
        'service_total',
        'parts_total',
        'subtotal',
        'discount_amount',
        'tax_amount',
        'total_amount',
        'payment_status',
        'payment_method',
        'issued_at',
        'paid_at',
        'note',
    ];

    /**
     * Ép kiểu dữ liệu.
     */
    protected function casts(): array
    {
        return [
            'service_total' =>
                'decimal:2',

            'parts_total' =>
                'decimal:2',

            'subtotal' =>
                'decimal:2',

            'discount_amount' =>
                'decimal:2',

            'tax_amount' =>
                'decimal:2',

            'total_amount' =>
                'decimal:2',

            'issued_at' =>
                'datetime',

            'paid_at' =>
                'datetime',
        ];
    }

    /**
     * Hóa đơn thuộc một
     * phiếu bảo dưỡng.
     */
    public function serviceOrder(): BelongsTo
    {
        return $this->belongsTo(
            ServiceOrder::class,
            'service_order_id'
        );
    }

    /**
     * Hóa đơn thuộc khách hàng.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(
            Customer::class,
            'customer_id'
        );
    }

    /**
     * Nhân viên lập hóa đơn.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'created_by'
        );
    }

    /**
     * Các dòng chi tiết hóa đơn.
     */
    public function items(): HasMany
    {
        return $this->hasMany(
            InvoiceItem::class,
            'invoice_id'
        );
    }
}