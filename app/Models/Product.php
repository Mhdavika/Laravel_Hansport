<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'image',
        'price',
        'discount_price',
        'stock',
        'description',
        'desc_image_1',
        'desc_image_2',
        'desc_image_3',
        'size_options',
        'has_size',
        'is_deal',
        'deal_end_date',
        'is_new',
        'is_best_seller',
    ];

    // Relasi ke kategori (setiap produk dimiliki satu kategori)
    // Model Product
public function category()
{
    return $this->belongsTo(Category::class);
}


    // Relasi ke ukuran produk (ProductSize)
   // app/Models/Product.php
public function sizes()
{
    return $this->hasMany(ProductSize::class);
}



    // Relasi dengan likes (jika ada)
    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    // Accessor untuk mengubah size_options (string) menjadi array ukuran
    public function getSizeListAttribute()
    {
        return array_filter(explode(',', $this->size_options));
    }
}
