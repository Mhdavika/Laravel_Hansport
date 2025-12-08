<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Blog;
use App\Models\User;
use App\Models\Order;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->search;

        $products = Product::where('name', 'like', "%{$q}%")->get();
        $blogs = Blog::where('title', 'like', "%{$q}%")->get();
        $users = User::where('name', 'like', "%{$q}%")->orWhere('email', 'like', "%{$q}%")->get();
        $orders = Order::where('name', 'like', "%{$q}%")->orWhere('email', 'like', "%{$q}%")->get();

        return view('admin.search.index', compact('q', 'products', 'blogs', 'users', 'orders'));
    }
}