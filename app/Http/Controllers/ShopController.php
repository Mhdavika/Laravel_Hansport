<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category');

        if ($request->has('category')) {
            $category = $request->category;

            // ✅ Produk Terbaru (flag is_new = 1)
            if ($category === 'Produk Terbaru') {
                $query->where('is_new', 1)->orderBy('created_at', 'desc');
            }

            // ✅ Produk Unggulan (flag is_best_seller = 1)
            elseif ($category === 'Produk Unggulan') {
                $query->where('is_best_seller', 1);
            }

            // ✅ Kategori biasa (Basket, Sepak Bola, Badminton, dll.)
            else {
                $query->whereHas('category', function ($q) use ($category) {
                    $q->where('name', $category);
                });
            }
        }

        if ($request->has('min_price') && $request->has('max_price')) {
            $query->whereBetween('price', [
                $request->min_price,
                $request->max_price
            ]);
        }

        $products = $query->get();

        return view('frontend.shop.index', compact('products'));
    }

    public function show($id)
    {
        $product = Product::with('category')->findOrFail($id);
        return view('product_detail', compact('product'));
    }
}