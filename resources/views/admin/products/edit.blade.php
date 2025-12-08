@extends('layouts.backend')

@section('title', 'Edit Produk')

@section('content')
<h1 class="h3 mb-4 text-gray-800">Edit Produk: {{ $product->name }}</h1>

<form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="form-group">
        <label>Nama Produk</label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $product->name) }}">
    </div>

    <div class="form-group">
        <label for="category_id">Kategori</label>
        <select name="category_id" id="category_id" class="form-control" required>
            <option value="">-- Pilih Kategori --</option>
            @foreach ($categories as $category)
            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                {{ $category->name }}
            </option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label>Harga</label>
        <input type="number" name="price" class="form-control" value="{{ old('price', $product->price) }}">
    </div>

    <div class="form-group">
        <label>Harga Diskon (opsional)</label>
        <input type="number" name="discount_price" class="form-control" value="{{ old('discount_price', $product->discount_price) }}">
    </div>


    <div class="form-group">
        <label>Stok</label>
        <input type="number" name="stock" class="form-control" value="{{ old('stock', $product->stock) }}">
    </div>

    <div class="form-group">
        <label>Deskripsi</label>
        <textarea name="description" class="form-control">{{ old('description', $product->description) }}</textarea>
    </div>

    <div class="form-group">
        <label>Opsi Ukuran</label>
        <input type="text" name="size_options" class="form-control" value="{{ old('size_options', $product->size_options) }}">
    </div>

    <div class="form-group">
        <label>Tanggal Berakhir Deal</label>
        <input type="date" name="deal_end_date" class="form-control"
            value="{{ old('deal_end_date', \Carbon\Carbon::parse($product->deal_end_date)->format('Y-m-d')) }}">
    </div>

    <div class="form-check">
        <input type="checkbox" name="is_new" class="form-check-input" id="is_new" {{ $product->is_new ? 'checked' : '' }}>
        <label class="form-check-label" for="is_new">Produk Baru</label>
    </div>

    <div class="form-check">
        <input type="checkbox" name="is_best_seller" class="form-check-input" id="is_best_seller" {{ $product->is_best_seller ? 'checked' : '' }}>
        <label class="form-check-label" for="is_best_seller">Best Seller</label>
    </div>

    <div class="form-check">
        <input type="checkbox" name="has_size" class="form-check-input" id="has_size" {{ $product->has_size ? 'checked' : '' }}>
        <label class="form-check-label" for="has_size">Punya Ukuran</label>
    </div>

    <div class="form-check mb-2">
        <input type="checkbox" name="is_deal" class="form-check-input" id="is_deal"
            {{ $product->is_deal ? 'checked' : '' }}>
        <label class="form-check-label" for="is_deal">Tampilkan sebagai "Deal of the Week"</label>
    </div>

    <div class="form-group">
        <label>Gambar Utama</label>
        <input type="file" name="image" class="form-control">
    </div>
    <div class="form-group">
        <label>Gambar Detail 1</label>
        <input type="file" name="desc_image_1" class="form-control">
    </div>
    <div class="form-group">
        <label>Gambar Detail 2</label>
        <input type="file" name="desc_image_2" class="form-control">
    </div>
    <div class="form-group">
        <label>Gambar Detail 3</label>
        <input type="file" name="desc_image_3" class="form-control">
    </div>

    <button type="submit" class="btn btn-primary mt-3">Simpan Perubahan</button>
</form>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const selectType = document.getElementById('size_option');
    const wrapper    = document.getElementById('size_stock_wrapper');
    const apparel    = document.getElementById('size_group_apparel');
    const shoes      = document.getElementById('size_group_shoes');

    function updateView() {
        wrapper.style.display = 'none';
        apparel.style.display = 'none';
        shoes.style.display   = 'none';

        if (selectType.value === 'apparel') {
            wrapper.style.display = 'block';
            apparel.style.display = 'block';
        } else if (selectType.value === 'shoes') {
            wrapper.style.display = 'block';
            shoes.style.display   = 'block';
        }
    }

    selectType.addEventListener('change', updateView);
    updateView();
});
</script>

@endsection