<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductSize;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        // Validasi data produk
        $validated = $request->validate([
            'name' => 'required',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric',
            'stock' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Menyimpan gambar jika ada
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images', 'public');
        }

        // Menyimpan data produk ke database
        $product = Product::create([
            'name' => $validated['name'],
            'category_id' => $validated['category_id'],
            'price' => $validated['price'],
            'stock' => $validated['stock'],
           'size_options' => is_array($request->input('sizes')) ? implode(',', $request->input('sizes')) : $request->input('sizes'), // Pastikan size_options dikirim sebagai array
            'image' => $imagePath ?? null,  // Menyimpan path gambar
        ]);

        // Menyimpan stok per ukuran jika produk memiliki ukuran
        if ($request->has('sizes')) {
            $sizeStock = $request->input('size_stock', []);
            foreach ($sizeStock as $size => $stock) {
                ProductSize::create([
                    'product_id' => $product->id,
                    'size' => $size,
                    'stock' => $stock,
                ]);
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $product    = Product::findOrFail($id);
        $categories = Category::all();

        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'category_id'     => 'required|exists:categories,id',
            'name'            => 'required|string|max:255',
            'price'           => 'required|numeric',
            'stock'           => 'required|integer',
            'description'     => 'nullable|string',
            'size_options'    => 'nullable|string',
            'discount_price'  => 'nullable|numeric',
            'deal_end_date'   => 'nullable|date',
            'image'           => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'desc_image_1'    => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'desc_image_2'    => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'desc_image_3'    => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $data = $request->only([
            'category_id', 'name', 'price', 'stock',
            'description', 'size_options', 'discount_price', 'deal_end_date'
        ]);

        $data['is_new']         = $request->has('is_new');
        $data['is_best_seller'] = $request->has('is_best_seller');
        $data['has_size']       = $request->has('has_size');
        $data['is_deal']        = $request->has('is_deal');

        $uploadAndCrop = function ($field) use ($request) {
            if ($request->hasFile($field)) {
                $manager = new ImageManager(Driver::class);
                $image   = $manager->read($request->file($field))->cover(800, 800);
                $path    = 'products/' . uniqid() . '.jpg';
                Storage::disk('public')->put($path, (string) $image->toJpeg());
                return $path;
            }
            return null;
        };

        if ($new = $uploadAndCrop('image'))         $data['image']        = $new;
        if ($new = $uploadAndCrop('desc_image_1'))  $data['desc_image_1'] = $new;
        if ($new = $uploadAndCrop('desc_image_2'))  $data['desc_image_2'] = $new;
        if ($new = $uploadAndCrop('desc_image_3'))  $data['desc_image_3'] = $new;

        $product->update($data);

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function show($id)
    {
        $product = Product::with('category')->findOrFail($id); // Pastikan mengambil data produk lengkap
        return view('frontend.product.show', compact('product')); // Kirim data produk ke view
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        
        Storage::disk('public')->delete(array_filter([
            $product->image,
            $product->desc_image_1,
            $product->desc_image_2,
            $product->desc_image_3,
        ]));

        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil dihapus.');
    }
}
