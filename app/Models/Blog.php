<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    protected $fillable = [
    'title',
    'type',
    'image',
    'content',
    'author',
    'published_at',
    'original_price',
    'promo_price',
    'discount_percent',
    'promo_start',     // ← baru
    'promo_end', 
];

   protected $casts = [
    'published_at' => 'datetime',
    'promo_start'  => 'datetime',
    'promo_end'    => 'datetime',
];

}
