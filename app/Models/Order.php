<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'phone',
        'postal_code',
        'province',
        'city',
        'district',
        'address',
        'payment_method',
        'bank_name',
        'ewallet_name',
        'total_price',
        'status',
        'proof_file',
        'expires_at',   // ⬅️ batas waktu pembayaran
    ];

    protected $casts = [
        'expires_at' => 'datetime', // ⬅️ biar bisa $order->expires_at->format(...)
    ];

    // relasi kalau ada
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
