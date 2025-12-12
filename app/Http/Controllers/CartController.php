<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Tampilkan halaman cart.
     */
    public function index()
    {
        $user = Auth::user();

        $cartItems = Cart::with('product')
            ->where('user_id', $user->id)
            ->get();

        $cartTotal = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        return view('frontend.cart.index', compact('cartItems', 'cartTotal'));
    }

    /**
     * Tambah produk ke cart.
     * Dipakai saat klik "Tambah ke keranjang".
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'nullable|integer|min:1',
            'size'       => 'nullable|string|max:50',
        ]);

        $user = Auth::user();
        $product = Product::findOrFail($request->product_id);

        $qty = $request->input('quantity', 1);

        // cek apakah item dengan product & size yang sama sudah ada di cart
        $cartItem = Cart::where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->when($request->size, function ($q) use ($request) {
                $q->where('size', $request->size);
            })
            ->first();

        if ($cartItem) {
            $cartItem->quantity += $qty;
            $cartItem->save();
        } else {
            Cart::create([
                'user_id'    => $user->id,
                'product_id' => $product->id,
                'quantity'   => $qty,
                'size'       => $request->size, // boleh null
            ]);
        }

        return redirect()->back()->with('success', 'Produk berhasil ditambahkan ke keranjang.');

    }

    /**
     * Update jumlah produk di cart (tombol + dan -).
     * Di view kamu mengirim field "action" = increase / decrease.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'action' => 'required|in:increase,decrease',
        ]);

        $user = Auth::user();

        $cartItem = Cart::with('product')
            ->where('user_id', $user->id)
            ->where('id', $id)
            ->firstOrFail();

        if ($request->action === 'increase') {
            // batasi sesuai stok produk
            if ($cartItem->quantity < $cartItem->product->stock) {
                $cartItem->quantity++;
            }
        } else {
            // decrease
            if ($cartItem->quantity > 1) {
                $cartItem->quantity--;
            }
        }

        $cartItem->save();

        return redirect()->back();
    }

    /**
     * Hapus item dari cart.
     */
    public function remove($id)
    {
        $user = Auth::user();

        Cart::where('user_id', $user->id)
            ->where('id', $id)
            ->delete();

        return redirect()->back()->with('success', 'Produk dihapus dari keranjang.');
    }

    public function destroy($id){ return $this->remove($id); }

}
