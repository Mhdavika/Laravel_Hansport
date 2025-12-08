<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Tampilkan semua pesanan dari user ke halaman admin.
     */
    public function index()
    {
        // Ambil semua pesanan beserta relasi user yang memesan
        $orders = Order::with('user')->latest()->get();

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Tampilkan detail satu pesanan tertentu (opsional).
     */
    public function show($id)
    {
        $order = Order::with(['user', 'items.product'])->findOrFail($id);

        return view('admin.orders.show', compact('order'));
    }

    /**
     * Ubah status pesanan (opsional).
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,proses,dikirim,selesai,dibatalkan'
        ]);

        $order = Order::findOrFail($id);
        $order->status = $request->status;
        $order->save();

        return redirect()->route('admin.orders.index')->with('success', 'Status pesanan berhasil diperbarui.');
    }
}
