<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Customer extends Model
{
    use HasFactory;

    /**
     * Các trường được phép gán hàng loạt.
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
     * Mỗi hồ sơ khách hàng có thể thuộc một tài khoản người dùng.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}