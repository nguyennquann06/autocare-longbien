<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceOrderPart extends Model
{
    use HasFactory;

    /**
     * Các trường được phép gán hàng loạt.
     */
    protected $fillable = [
        'service_order_id',
        'part_id',
        'part_code',
        'part_name',
        'unit',
        'unit_price',
        'quantity',
        'line_total',
        'note',
    ];

    /**
     * Ép kiểu dữ liệu.
     */
    protected function casts(): array
    {
        return [
            'unit_price' => 'decimal:2',

            'quantity' => 'integer',

            'line_total' => 'decimal:2',
        ];
    }

    /**
     * Phụ tùng sử dụng thuộc
     * một phiếu bảo dưỡng.
     */
    public function serviceOrder(): BelongsTo
    {
        return $this->belongsTo(
            ServiceOrder::class,
            'service_order_id'
        );
    }

    /**
     * Liên kết tới phụ tùng hiện tại.
     *
     * Có thể null nếu phụ tùng gốc
     * không còn tồn tại.
     */
    public function part(): BelongsTo
    {
        return $this->belongsTo(
            Part::class,
            'part_id'
        );
    }
}