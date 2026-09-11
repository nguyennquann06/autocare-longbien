<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceOrderItem extends Model
{
    use HasFactory;

    /**
     * Các trường được phép gán dữ liệu hàng loạt.
     */
    protected $fillable = [
        'service_order_id',
        'service_id',
        'service_name',
        'unit_price',
        'quantity',
        'line_total',
        'estimated_duration_minutes',
        'status',
        'technician_note',
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

            'estimated_duration_minutes' =>
                'integer',
        ];
    }

    /**
     * Hạng mục thuộc một phiếu bảo dưỡng.
     */
    public function serviceOrder(): BelongsTo
    {
        return $this->belongsTo(
            ServiceOrder::class,
            'service_order_id'
        );
    }

    /**
     * Hạng mục liên kết tới dịch vụ gốc.
     */
    public function service(): BelongsTo
    {
        return $this->belongsTo(
            Service::class,
            'service_id'
        );
    }
}