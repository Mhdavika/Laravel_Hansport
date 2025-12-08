<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Like;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function toggle($productId)
    {
        $user = Auth::user();

        $like = Like::where('user_id', $user->id)->where('product_id', $productId)->first();

        if ($like) {
            $like->delete();
        } else {
            Like::create([
                'user_id' => $user->id,
                'product_id' => $productId,
            ]);
        }

        return redirect()->back();
    }

    public function likedProducts()
    {
        $user = Auth::user();

        // Ambil daftar produk yang disukai
        $products = $user->likes->map(fn ($like) => $like->product);

        return view('frontend.profile.likes', compact('products'));
    }
}
