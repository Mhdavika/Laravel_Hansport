<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Cart;
use App\Models\ProductSize;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Tampilkan halaman keranjang.
     */
   public function index()
{
    $cartItems = Cart::where('user_id', Auth::id())->with('product')->get(); // Mengambil semua item keranjang untuk user yang sedang login
    $cartTotal = $cartItems->sum(function($item) {
        return $item->product->price * $item->quantity; // Menghitung total harga keranjang
    });

    return view('frontend.cart.index', compact('cartItems', 'cartTotal'));
}

    public function create()
    {
        //
    }

    /**
     * Tambah produk ke keranjang.
     * - Kalau belum login: diarahkan ke halaman login
     * - Kalau sudah login: produk dimasukkan ke keranjang
     */
  public function store(Request $request)
{
    // Validasi
    $product = Product::findOrFail($request->product_id);
    $size = $request->size;

    // Validasi jika ukuran tidak dipilih
    if ($product->has_size && !$size) {
        return redirect()->back()->with('error', 'Silakan pilih ukuran produk.');
    }

    // Cek stok berdasarkan ukuran jika produk punya ukuran
    if ($product->has_size) {
        $productSize = ProductSize::where('product_id', $product->id)
            ->where('size', $size)
            ->first();

        if (!$productSize || $productSize->stock <= 0) {
            return redirect()->back()->with('error', 'Ukuran yang dipilih tidak tersedia.');
        }
    }

    // Simpan ke keranjang
    Cart::create([
        'user_id' => Auth::id(),
        'product_id' => $product->id,
        'quantity' => $request->quantity,
        'size' => $size, // Menyimpan ukuran yang dipilih
    ]);

    return redirect()->route('cart.index')->with('success', 'Produk berhasil ditambahkan ke keranjang.');
}
    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    /**
     * Update quantity produk di keranjang.
     */
    public function update(Request $request, $id)
    {
        $cartItem = Cart::where('user_id', Auth::id())
            ->where('id', $id)
            ->firstOrFail();

        $product = $cartItem->product;
        $action  = $request->input('action');

        if ($action === 'increase' && $cartItem->quantity < $product->stock) {
            $cartItem->increment('quantity');
        }

        if ($action === 'decrease' && $cartItem->quantity > 1) {
            $cartItem->decrement('quantity');
        }

        return back()->with('success', 'Jumlah produk diperbarui.');
    }

    /**
     * Hapus item dari keranjang.
     */
    public function destroy($id)
    {
        $cartItem = Cart::where('user_id', Auth::id())
            ->where('id', $id)
            ->first();

        if ($cartItem) {
            $cartItem->delete();
        }

        return back()->with('success', 'Produk berhasil dihapus dari keranjang!');
    }
}
