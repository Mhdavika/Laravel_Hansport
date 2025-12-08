<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Order;
use App\Models\User;
use App\Models\Chat;

class DashboardController extends Controller
{
    public function index()
    {
        // Hitung jumlah pesan yang belum dibaca oleh admin
        $unreadChatsCount = Chat::where('receiver_id', auth()->id()) // Pesan yang dikirim ke admin
                                ->count();

        return view('admin.dashboard', [
            'totalProducts' => Product::count(),
            'totalOrders' => Order::count(),
            'totalCustomers' => User::where('role', 'user')->count(),
            'totalRevenue' => Order::sum('total_price'),
            'unreadChatsCount' => $unreadChatsCount,  // Tambahkan variabel unreadChatsCount
        ]);
    }
}
