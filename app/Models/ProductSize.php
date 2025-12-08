<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductSize extends Model
{
    protected $fillable = [
        'product_id',
        'size',
        'stock',
    ];

    /**
     * Relasi ke model Product (setiap ukuran produk terkait dengan satu produk)
     */
    public function product()
{
    return $this->belongsTo(Product::class);
}

}
