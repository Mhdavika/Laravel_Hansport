<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BlogController extends Controller
{
    /**
     * Tampilkan daftar Info & Promo (Blog).
     */
    public function index()
    {
        $blogs = Blog::orderByDesc('published_at')
            ->orderByDesc('created_at')
            ->get();

        return view('admin.blogs.index', compact('blogs'));
    }

    /**
     * Form tambah Info / Promo.
     */
    public function create()
    {
        return view('admin.blogs.create');
    }

    /**
     * Simpan Info / Promo baru.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title'            => 'required|string|max:255',
            'type'             => 'required|in:info,promo',
            'image'            => 'nullable|image|max:5120',
            'content'          => 'required|string',
            'author'           => 'nullable|string|max:255',
            'published_at'     => 'nullable|date',
            'original_price'   => 'nullable|integer',
            'promo_price'      => 'nullable|integer',
            'discount_percent' => 'nullable|integer',
            'promo_start'      => 'nullable|date',
            'promo_end'        => 'nullable|date|after_or_equal:promo_start',
        ]);

        // ==============================
        // AUTO HITUNG HARGA & DISKON
        // ==============================
        $original = $data['original_price']   ?? null;
        $promo    = $data['promo_price']      ?? null;
        $percent  = $data['discount_percent'] ?? null;

        // Jika ada harga asli + persen diskon → hitung harga promo
        if ($original && $percent && !$promo) {
            $data['promo_price'] = round($original - ($original * ($percent / 100)));
        }

        // Jika ada harga asli + harga promo → hitung persen diskon
        if ($original && $promo && !$percent) {
            $data['discount_percent'] = round((($original - $promo) / $original) * 100);
        }

        // Jika ada harga promo + persen diskon → hitung harga asli
        if ($promo && $percent && !$original) {
            $data['original_price'] = round($promo / (1 - ($percent / 100)));
        }

        // Upload gambar jika ada
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('blogs', 'public');
        }

        // Default author
        if (empty($data['author'])) {
            $data['author'] = 'Admin Hansport';
        }

        // Default tanggal publish
        if (empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        Blog::create($data);

        return redirect()
            ->route('admin.blogs.index')
            ->with('success', 'Info / Promo berhasil ditambahkan.');
    }

    /**
     * Form edit Info / Promo.
     */
    public function edit(Blog $blog)
    {
        return view('admin.blogs.edit', compact('blog'));
    }

    /**
     * Update Info / Promo.
     */
    public function update(Request $request, $id)
    {
        $blog = Blog::findOrFail($id);

        $data = $request->validate([
            'title'            => 'required|string|max:255',
            'type'             => 'required|in:info,promo',
            'image'            => 'nullable|image|max:5120',
            'content'          => 'required|string',
            'author'           => 'nullable|string|max:255',
            'published_at'     => 'nullable|date',
            'original_price'   => 'nullable|integer',
            'promo_price'      => 'nullable|integer',
            'discount_percent' => 'nullable|integer',
            'promo_start'      => 'nullable|date',
            'promo_end'        => 'nullable|date|after_or_equal:promo_start',
        ]);

        // ==============================
        // AUTO HITUNG HARGA & DISKON
        // ==============================
        $original = $data['original_price']   ?? null;
        $promo    = $data['promo_price']      ?? null;
        $percent  = $data['discount_percent'] ?? null;

        // Jika ada harga asli + persen diskon → hitung harga promo
        if ($original && $percent && !$promo) {
            $data['promo_price'] = round($original - ($original * ($percent / 100)));
        }

        // Jika ada harga asli + harga promo → hitung persen diskon
        if ($original && $promo && !$percent) {
            $data['discount_percent'] = round((($original - $promo) / $original) * 100);
        }

        // Jika ada harga promo + persen diskon → hitung harga asli
        if ($promo && $percent && !$original) {
            $data['original_price'] = round($promo / (1 - ($percent / 100)));
        }

        // Gambar baru? hapus lama dulu
        if ($request->hasFile('image')) {
            if ($blog->image && Storage::disk('public')->exists($blog->image)) {
                Storage::disk('public')->delete($blog->image);
            }

            $data['image'] = $request->file('image')->store('blogs', 'public');
        }

        // Kalau author kosong, pakai default
        if (empty($data['author'])) {
            $data['author'] = 'Admin Hansport';
        }

        $blog->update($data);

        return redirect()
            ->route('admin.blogs.index')
            ->with('success', 'Info / Promo berhasil diperbarui.');
    }

    /**
     * Hapus Info / Promo.
     */
    public function destroy(Blog $blog)
    {
        if ($blog->image && Storage::disk('public')->exists($blog->image)) {
            Storage::disk('public')->delete($blog->image);
        }

        $blog->delete();

        return redirect()
            ->route('admin.blogs.index')
            ->with('success', 'Info / Promo berhasil dihapus.');
    }
}
