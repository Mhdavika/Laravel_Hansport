<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class Cart extends Model
{
    protected $fillable = ['user_id', 'product_id', 'quantity', 'size'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    
}
