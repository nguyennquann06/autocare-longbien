<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Part extends Model
{
    use HasFactory;

    /**
     * Các trường được phép gán hàng loạt.
     */
    protected $fillable = [
        'code',
        'name',
        'category',
        'unit',
        'cost_price',
        'selling_price',
        'stock_quantity',
        'minimum_stock',
        'description',
        'is_active',
    ];

    /**
     * Ép kiểu dữ liệu.
     */
    protected function casts(): array
    {
        return [
            'cost_price' => 'decimal:2',

            'selling_price' => 'decimal:2',

            'stock_quantity' => 'integer',

            'minimum_stock' => 'integer',

            'is_active' => 'boolean',
        ];
    }

    /**
     * Các lần phụ tùng này được sử dụng
     * trong phiếu bảo dưỡng.
     */
    public function serviceOrderParts(): HasMany
    {
        return $this->hasMany(
            ServiceOrderPart::class,
            'part_id'
        );
    }

    /**
     * Lịch sử nhập / xuất / điều chỉnh kho.
     */
    public function inventoryTransactions(): HasMany
    {
        return $this->hasMany(
            InventoryTransaction::class,
            'part_id'
        );
    }
}