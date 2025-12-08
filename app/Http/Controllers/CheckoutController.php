<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Log;
use Laravolt\Indonesia\Models\Province;

class CheckoutController extends Controller
{
    /**
     * Tampilkan halaman checkout.
     * Hanya untuk item yang dipilih dari keranjang (cart_ids[]).
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Ambil data provinsi untuk dropdown alamat
        $provinces = Province::orderBy('name')->get();

        // Terima parameter `cart_ids` (GET) atau `cart_items`, atau fallback ke session
        $cartIds = $request->input('cart_ids', $request->input('cart_items', session('checkout_cart_ids', [])));

        if (empty($cartIds)) {
            return redirect()->route('cart.index')->with('error', 'Silakan pilih minimal satu produk untuk checkout.');
        }

        // Simpan di session supaya bisa dipakai di submit() dan finalize()
        session(['checkout_cart_ids' => $cartIds]);

        $cartItems = Cart::with('product')
            ->where('user_id', $user->id)
            ->whereIn('id', $cartIds)
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Data keranjang tidak ditemukan.');
        }

        $total = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        return view('frontend.checkout.index', compact('user', 'cartItems', 'total', 'provinces'));
    }

    /**
     * Tangani data checkout (disimpan di session, bukan database).
     */
    public function submit(Request $request)
    {
        $request->validate([
            'name'           => 'required|string',
            'email'          => 'required|email',
            'phone'          => 'required|string',
            'postal_code'    => 'required|string',
            'province'       => 'required|string',
            'city'           => 'required|string',
            'district'       => 'required|string',
            'address'        => 'required|string',
            // COD DIHAPUS → hanya transfer & ewallet
            'payment_method' => 'required|in:transfer,ewallet',
            'bank_name'      => 'required_if:payment_method,transfer',
            'ewallet_name'   => 'required_if:payment_method,ewallet',
        ]);

        $user = Auth::user();

        // Ambil cart_ids yang dipilih dari session
        $cartIds = session('checkout_cart_ids', []);

        if (empty($cartIds)) {
            return redirect()->route('cart.index')->with('error', 'Produk untuk checkout tidak ditemukan. Silakan pilih ulang.');
        }

        $cartItems = Cart::with('product')
            ->where('user_id', $user->id)
            ->whereIn('id', $cartIds)
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang kamu kosong.');
        }

        // Cek stok per item terpilih
        foreach ($cartItems as $item) {
            $product = $item->product;
            if (!$product) {
                return redirect()->back()->with('error', 'Produk tidak ditemukan.');
            }
            if ($product->stock < $item->quantity) {
                return redirect()->back()->with('error', 'Stok produk "' . $product->name . '" tidak mencukupi. Maksimal tersedia: ' . $product->stock);
            }
        }

        $total = $cartItems->sum(fn($item) => $item->product->price * $item->quantity);

        $orderData = [
            'user_id'        => $user->id,
            'name'           => $request->name,
            'email'          => $request->email,
            'phone'          => $request->phone,
            'postal_code'    => $request->postal_code,
            'province'       => $request->province,
            'city'           => $request->city,
            'district'       => $request->district,
            'address'        => $request->address,
            'payment_method' => $request->payment_method,
            'bank_name'      => $request->bank_name,
            'ewallet_name'   => $request->ewallet_name,
            'total_price'    => $total,
            'status'         => 'pending',
            // ⬇️ batas waktu pembayaran: 24 jam dari sekarang
            'expires_at'     => now()->addMinutes(1),
        ];

        session(['checkout_data' => $orderData]);

        return redirect()->route('checkout.confirm');
    }

    /**
     * Tampilkan halaman konfirmasi pembayaran.
     */
    public function confirm()
    {
        $data = session('checkout_data');

        if (!$data) {
            return redirect()->route('checkout.index')->with('error', 'Data konfirmasi tidak ditemukan.');
        }

        return view('frontend.checkout.confirm', [
            'payment' => $data['payment_method'],
            'bank'    => $data['bank_name'],
            'ewallet' => $data['ewallet_name'],
        ]);
    }

    /**
     * Finalisasi pesanan (simpan ke database).
     * - Bukti pembayaran: hanya gambar (jpg,jpeg,png,webp) max 5MB.
     * - Cek juga apakah sudah melewati batas waktu pembayaran.
     */
    public function finalize(Request $request)
    {
        // Validasi bukti pembayaran
        $request->validate(
            [
                'proof' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120', // 5MB
            ],
            [
                'proof.image' => 'File bukti harus berupa gambar.',
                'proof.mimes' => 'Format file tidak sesuai. Gunakan JPG, JPEG, PNG, atau WEBP.',
                'proof.max'   => 'Ukuran file maksimal 5MB.',
            ]
        );

        $user    = Auth::user();
        $data    = session('checkout_data');
        $cartIds = session('checkout_cart_ids', []);

        if (!$data || empty($cartIds)) {
            return redirect()->route('checkout.index')->with('error', 'Data tidak ditemukan.');
        }

        // Cek batas waktu pembayaran sebelum simpan
        if (!empty($data['expires_at']) && now()->greaterThan($data['expires_at'])) {
            return redirect()->route('cart.index')->with('error', 'Batas waktu pembayaran sudah berakhir. Silakan buat pesanan baru.');
        }

        // Simpan order ke database (expires_at sudah ada di $data)
        $order = Order::create($data);
        session(['order_id' => $order->id]);

        // Upload bukti pembayaran (jika ada)
        if ($request->hasFile('proof')) {
            $path = $request->file('proof')->store('bukti-transfer', 'public');
            $order->proof_file = $path;
        }

        // Simpan item pesanan hanya dari cart_ids yang dipilih
        $cartItems = Cart::with('product')
            ->where('user_id', $user->id)
            ->whereIn('id', $cartIds)
            ->get();

        foreach ($cartItems as $item) {
            OrderItem::create([
                'order_id'     => $order->id,
                'product_id'   => $item->product_id,
                'product_name' => $item->product->name ?? 'Tidak diketahui',
                'quantity'     => $item->quantity,
                'price'        => $item->product->price,
                'size'         => $item->size,
                'subtotal'     => $item->product->price * $item->quantity,
            ]);

            $product = $item->product;
            if ($product && $product->stock >= $item->quantity) {
                $product->stock -= $item->quantity;
                $product->save();
            } else {
                Log::warning("Stok tidak cukup untuk produk {$product->name} (ID: {$product->id})");
            }
        }

        // Hapus hanya cart yang sudah di-checkout
        Cart::where('user_id', $user->id)
            ->whereIn('id', $cartIds)
            ->delete();

        $order->save();

        // Bersihkan session
        session()->forget('checkout_data');
        session()->forget('checkout_cart_ids');

        return redirect()->route('checkout.success')->with('success', 'Pesanan berhasil dibuat! Silakan lakukan pembayaran sebelum batas waktu yang ditentukan.');
    }

    /**
     * Upload bukti pembayaran (kalau masih mau dipakai terpisah).
     * Sekarang juga dibatasi hanya gambar max 5MB.
     */
    public function uploadProof(Request $request)
    {
        $request->validate(
            [
                'proof' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
            ],
            [
                'proof.image' => 'File bukti harus berupa gambar.',
                'proof.mimes' => 'Format file tidak sesuai. Gunakan JPG, JPEG, PNG, atau WEBP.',
                'proof.max'   => 'Ukuran file maksimal 5MB.',
            ]
        );

        $path = $request->file('proof')->store('bukti-transfer', 'public');

        session([
            'payment_method'  => 'transfer',
            'proof_uploaded'  => $path,
        ]);

        return redirect()->route('checkout.success')->with('success', 'Bukti pembayaran berhasil dikirim!');
    }

    /**
     * Halaman sukses setelah proses selesai.
     */
    public function success()
    {
        return view('frontend.checkout.success');
    }
}
