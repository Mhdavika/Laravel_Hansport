<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Blog;

class HomeController extends Controller
{
   public function index()
{
    // Ambil produk terbaru (is_new = 1), maksimal 15 produk, termasuk relasi kategori
  $newProducts = Product::latest()->limit(15)->get();


    // Ambil produk unggulan (is_best_seller = 1), maksimal 10 produk, termasuk relasi kategori
    $bestSellers = Product::where('is_best_seller', 1)
        ->with('category')
        ->take(15)
        ->get();

    // Ambil satu produk untuk "Deal of the Week" yang masih aktif
    $dealProduct = Product::where('is_deal', 1)
        ->where('deal_end_date', '>', now())
        ->latest()
        ->first();

    // Ambil 3 blog terbaru
    $blogs = Blog::latest()->limit(3)->get();

    // Kirim data ke view frontend.homepage
    return view('frontend.homepage', compact('newProducts', 'bestSellers', 'dealProduct', 'blogs'));
}

}
