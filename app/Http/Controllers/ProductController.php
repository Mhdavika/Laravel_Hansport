<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Cart;
use App\Models\User;

class ProductController extends Controller
{
    public function show($id)
    {
        $product = Product::with('category')->findOrFail($id);
        return view('frontend.product.show', compact('product'));
    }


    public function preview($id)
    {
        $product = Product::findOrFail($id);
        return view('frontend.product.preview', compact('product'));
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
                'size' => 'required|in:' . implode(',', $product->size_list),
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
