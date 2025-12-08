<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

use App\Models\Order;
use App\Models\Cart;
use App\Models\OrderItem;

use Laravolt\Indonesia\Models\Province;
use Laravolt\Indonesia\Models\City;
use App\Models\Kabupaten; // kalau nggak dipakai boleh dihapus

class CheckoutController extends Controller
{
    /**
     * Tampilkan halaman checkout.
     * Hanya untuk item yang dipilih dari keranjang (cart_ids[]).
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // ✅ SUDAH 1 INDONESIA: ambil semua provinsi dari Laravolt
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
            'payment_method' => 'required|in:transfer,ewallet',
            'bank_name'      => 'required_if:payment_method,transfer',
            'ewallet_name'   => 'required_if:payment_method,ewallet',
        ]);

        $user = Auth::user();

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
            // ⬇️ batas waktu pembayaran
            'expires_at'     => now()->addHours(24),
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
     */
    public function finalize(Request $request)
    {
        $request->validate(
            [
                'proof' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
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

        if (!empty($data['expires_at']) && now()->greaterThan($data['expires_at'])) {
            return redirect()->route('cart.index')->with('error', 'Batas waktu pembayaran sudah berakhir. Silakan buat pesanan baru.');
        }

        $order = Order::create($data);
        session(['order_id' => $order->id]);

        if ($request->hasFile('proof')) {
            $path = $request->file('proof')->store('bukti-transfer', 'public');
            $order->proof_file = $path;
        }

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

        Cart::where('user_id', $user->id)
            ->whereIn('id', $cartIds)
            ->delete();

        $order->save();

        session()->forget('checkout_data');
        session()->forget('checkout_cart_ids');

        return redirect()->route('checkout.success')->with('success', 'Pesanan berhasil dibuat! Silakan lakukan pembayaran sebelum batas waktu yang ditentukan.');
    }

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

    public function success()
    {
        return view('frontend.checkout.success');
    }

    /**
     * ✅ Cek ongkir SE-INDONESIA berdasarkan jarak dari toko (Bogor) ke kota tujuan.
     */
    public function cekOngkir(Request $request)
    {
        $request->validate([
            'city_id'  => 'required',
            'subtotal' => 'required|numeric',
        ]);

        $subtotal = (int) $request->subtotal;
        $cityId   = $request->city_id;

        // Koordinat toko (Bogor)
        $asalLat = -6.599403325394194;
        $asalLng = 106.81231178112644;

        $city = City::findOrFail($cityId);

        // meta menyimpan JSON: {"lat":"...","long":"..."}
        $meta = is_array($city->meta) ? $city->meta : json_decode($city->meta, true);

        if (!$meta || empty($meta['lat']) || empty($meta['long'])) {
            // kalau koordinat belum ada → kasih ongkir default
            $jarakKm = null;
            $ongkir  = 40000;
        } else {
            $tujuanLat = (float) $meta['lat'];
            $tujuanLng = (float) $meta['long'];

            $jarakKm = $this->hitungJarak($asalLat, $asalLng, $tujuanLat, $tujuanLng);

            if ($jarakKm <= 20) {
                $ongkir = 10000;
            } elseif ($jarakKm <= 100) {
                $ongkir = 20000;
            } elseif ($jarakKm <= 250) {
                $ongkir = 40000;
            } elseif ($jarakKm <= 750) {
                $ongkir = 60000;
            } else {
                $ongkir = 100000;
            }
        }

        return response()->json([
            'jarak_km' => $jarakKm ? round($jarakKm, 2) : null,
            'ongkir'   => $ongkir,
            'total'    => $subtotal + $ongkir,
        ]);
    }

    /**
     * Helper hitung jarak (KM) pakai Haversine.
     */
    private function hitungJarak(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $earthRadius = 6371; // km

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }
}
