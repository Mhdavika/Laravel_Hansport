<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Cart;

class ProductController extends Controller
{
 // ProductController.php
// app/Http/Controllers/ProductController.php
public function show($id)
{
    $product = Product::with('sizes')->findOrFail($id); // Muat ukuran produk
    return view('frontend.product.show', compact('product'));
}






    public function store(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu untuk menambahkan ke keranjang.');
        }

        $product = Product::findOrFail($request->product_id);

        // Validasi ukuran jika produk punya ukuran
        if ($product->has_size) {
            $request->validate([
                'size' => 'required|in:' . implode(',', $product->sizes->pluck('size')->toArray()),
            ]);
        }

        // Validasi jumlah
        $request->validate([
            'quantity' => 'required|integer|min:1|max:' . $product->stock,
        ]);

    // Simpan ke keranjang
    Cart::create([
        'user_id'    => $user->id,
        'product_id' => $product->id,
        'quantity'   => $request->quantity,
        'size'       => $request->size ?? null,
    ]);

    return redirect()->back()->with('success', 'Produk berhasil ditambahkan ke keranjang.');
    }
}
