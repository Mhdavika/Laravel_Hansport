<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;

class InfoController extends Controller
{
    /**
     * Daftar Info & Promo
     */
    public function index()
    {
        $posts = Blog::orderByDesc('published_at')
            ->orderByDesc('created_at')
            ->paginate(6);

        return view('frontend.info.index', compact('posts'));
    }

    /**
     * Detail Info & Promo
     */
    public function show($id)
    {
        $post = Blog::findOrFail($id);

        return view('frontend.info.show', compact('post'));
    }
}
