<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InvoiceItem extends Model
{
    use HasFactory;

    /**
     * Loại dòng hóa đơn.
     */
    public const TYPE_SERVICE = 'SERVICE';

    public const TYPE_PART = 'PART';

    /**
     * Các trường được phép gán hàng loạt.
     */
    protected $fillable = [
        'invoice_id',
        'item_type',
        'source_id',
        'item_code',
        'item_name',
        'unit',
        'unit_price',
        'quantity',
        'line_total',
    ];

    /**
     * Ép kiểu dữ liệu.
     */
    protected function casts(): array
    {
        return [
            'source_id' =>
                'integer',

            'unit_price' =>
                'decimal:2',

            'quantity' =>
                'integer',

            'line_total' =>
                'decimal:2',
        ];
    }

    /**
     * Dòng chi tiết thuộc hóa đơn.
     */
    public function invoice(): BelongsTo
    {
        return $this->belongsTo(
            Invoice::class,
            'invoice_id'
        );
    }
}