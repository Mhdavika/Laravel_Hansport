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
        'color_options',
        'size_options',
        'has_size',
        'is_deal',
        'deal_end_date',
        'is_new',
        'is_best_seller',
    ];

    /**
     * Relasi ke kategori (setiap produk dimiliki satu kategori)
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Relasi ke tabel likes (satu produk bisa punya banyak like)
     */
    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    /**
     * Accessor untuk mengambil warna utama (jika ada banyak warna)
     */
    public function getColorValueAttribute()
    {
        return trim($this->color_options);
    }

    /**
     * Accessor untuk mengubah size_options (string) menjadi array ukuran
     */
    public function getSizeListAttribute()
    {
        return array_filter(explode(',', $this->size_options));
    }
}
