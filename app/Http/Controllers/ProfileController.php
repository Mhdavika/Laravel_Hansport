<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Order;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // Masih pakai mock orders (kalau mau nanti kita ganti pakai Order beneran)
        $orders = [
            (object)['product_name' => 'Jersey Lakers', 'quantity' => 1, 'total_price' => 200000],
            (object)['product_name' => 'Sepatu Nike Zoom', 'quantity' => 2, 'total_price' => 700000],
        ];

        return view('frontend.profile.index', compact('user', 'orders'));
    }

    public function edit()
    {
        $user = Auth::user();
        return view('frontend.profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|unique:users,email,' . Auth::id(),
            'phone'         => 'nullable|string|max:20',
            'address'       => 'nullable|string|max:255',
            'province'      => 'nullable|string|max:100',
            'city'          => 'nullable|string|max:100',
            'district'      => 'nullable|string|max:100',
            'postal_code'   => 'nullable|string|max:10',
            'profile_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($request->hasFile('profile_photo')) {
            $file = $request->file('profile_photo');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('frontend/profile_photos'), $filename);

            // Hapus foto lama jika ada
            if ($user->profile_photo && file_exists(public_path('frontend/profile_photos/' . $user->profile_photo))) {
                @unlink(public_path('frontend/profile_photos/' . $user->profile_photo));
            }

            $user->profile_photo = $filename;
        }

        $user->update([
            'name'          => $request->name,
            'email'         => $request->email,
            'phone'         => $request->phone,
            'address'       => $request->address,
            'province'      => $request->province,
            'city'          => $request->city,
            'district'      => $request->district,
            'postal_code'   => $request->postal_code,
            'profile_photo' => $user->profile_photo,
        ]);

        return redirect()->route('profile.index')->with('success', 'Profil berhasil diperbarui.');
    }

    public function orders()
    {
        $user = Auth::user();

        $orders = Order::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // Update otomatis jadi expired kalau sudah lewat batas
        foreach ($orders as $order) {
            if ($order->status === 'pending' && $order->expires_at && now()->greaterThan($order->expires_at)) {
                $order->status = 'expired';
                $order->save();
            }
        }

        return view('frontend.profile.orders', compact('orders'));
    }

    public function orderDetail($id)
    {
        $user = Auth::user();

        // Ambil order + item, tapi pastikan milik user yang login
        $order = Order::with('items.product')
            ->where('user_id', $user->id)
            ->findOrFail($id);

        if ($order->status === 'pending' && $order->expires_at && now()->greaterThan($order->expires_at)) {
            $order->status = 'expired';
            $order->save();
        }

        return view('frontend.profile.order-detail', compact('order'));
    }

    public function likes()
    {
        $user = Auth::user();

        // data dummy
        $likes = [
            (object)[
                'product_name' => 'Bola Basket Molten',
                'price'        => 250000,
                'image'        => 'basket2.png',
            ],
            (object)[
                'product_name' => 'Jersey Argentina',
                'price'        => 180000,
                'image'        => 'jersey-argentina.png',
            ],
        ];

        return view('frontend.profile.likes', compact('likes'));
    }

    public function uploadPhoto(Request $request)
    {
        $request->validate([
            'profile_photo' => 'image|max:2048',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($request->hasFile('profile_photo')) {
            $path = $request->file('profile_photo')->store('profile_photos', 'public');
            $user->profile_photo = $path;
            $user->save();
        }

        return back()->with('success', 'Foto profil berhasil diperbarui.');
    }

    public function editPassword()
    {
        return view('frontend.profile.password');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password'     => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password lama salah.']);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->route('profile.index')->with('success', 'Password berhasil diperbarui.');
    }
}
