<?php
namespace App\Http\Controllers\Frontend;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    // Fungsi untuk menampilkan detail pesanan
    public function show($orderId)
    {
        $order = Order::with('items.product')->findOrFail($orderId);
        return view('frontend.profile.order_detail', compact('order'));
    }
}
