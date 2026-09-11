<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryTransaction extends Model
{
    use HasFactory;

    /**
     * Các loại giao dịch kho.
     */
    public const TYPE_IN = 'IN';

    public const TYPE_OUT = 'OUT';

    public const TYPE_ADJUSTMENT = 'ADJUSTMENT';

    /**
     * Các trường được phép gán hàng loạt.
     */
    protected $fillable = [
        'part_id',
        'service_order_id',
        'performed_by',
        'transaction_type',
        'quantity',
        'quantity_before',
        'quantity_after',
        'unit_cost',
        'note',
        'transaction_at',
    ];

    /**
     * Ép kiểu dữ liệu.
     */
    protected function casts(): array
    {
        return [
            'quantity' => 'integer',

            'quantity_before' => 'integer',

            'quantity_after' => 'integer',

            'unit_cost' => 'decimal:2',

            'transaction_at' => 'datetime',
        ];
    }

    /**
     * Giao dịch thuộc một phụ tùng.
     */
    public function part(): BelongsTo
    {
        return $this->belongsTo(
            Part::class,
            'part_id'
        );
    }

    /**
     * Phiếu bảo dưỡng liên quan.
     *
     * Thường có khi transaction_type = OUT.
     */
    public function serviceOrder(): BelongsTo
    {
        return $this->belongsTo(
            ServiceOrder::class,
            'service_order_id'
        );
    }

    /**
     * Người thực hiện giao dịch kho.
     */
    public function performer(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'performed_by'
        );
    }
}