@extends('layouts.backend')

@section('title', 'Tambah Produk')

@section('content')

<h1 class="h3 mb-4 text-gray-800">Tambah Produk Baru</h1>

<form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <!-- Nama Produk -->
    <div class="form-group">
        <label>Nama Produk</label>
        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
    </div>

    <!-- Kategori -->
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

    <!-- Harga -->
    <div class="form-group">
        <label>Harga</label>
        <input type="number" name="price" class="form-control" value="{{ old('price') }}" required>
    </div>

    <!-- Harga Diskon -->
    <div class="form-group">
        <label>Harga Diskon (opsional)</label>
        <input type="number" name="discount_price" class="form-control" value="{{ old('discount_price') }}">
    </div>

    <!-- Deskripsi -->
    <div class="form-group">
        <label>Deskripsi</label>
        <textarea name="description" class="form-control">{{ old('description') }}</textarea>
    </div>

    <!-- Opsi Ukuran -->
    <div class="form-group">
        <label for="size_option">Pilih Ukuran</label>
        <select name="size_option" id="size_option" class="form-control" required>
            <option value="none">Tidak ada ukuran</option>
            <option value="apparel">S / M / L / XL</option>
            <option value="shoes">Ukuran Sepatu (39–44)</option>
        </select>
    </div>

    <!-- Fields for Size -->
    <div id="size_fields" style="display:none;">
        <!-- Ukuran Baju -->
        <div id="size_group_apparel" style="display:none;">
            <label>Stok per Ukuran (Baju)</label>
            <div class="form-row">
                <div class="col-md-3 mb-2">
                    <label>S</label>
                    <input type="number" name="size_stock[S]" class="form-control" value="0" min="0">
                </div>
                <div class="col-md-3 mb-2">
                    <label>M</label>
                    <input type="number" name="size_stock[M]" class="form-control" value="0" min="0">
                </div>
                <div class="col-md-3 mb-2">
                    <label>L</label>
                    <input type="number" name="size_stock[L]" class="form-control" value="0" min="0">
                </div>
                <div class="col-md-3 mb-2">
                    <label>XL</label>
                    <input type="number" name="size_stock[XL]" class="form-control" value="0" min="0">
                </div>
            </div>
        </div>

        <!-- Ukuran Sepatu -->
        <div id="size_group_shoes" style="display:none;">
            <label>Stok per Ukuran (Sepatu)</label>
            <div class="form-row">
                @foreach([39, 40, 41, 42, 43, 44] as $shoeSize)
                    <div class="col-md-2 mb-2">
                        <label>{{ $shoeSize }}</label>
                        <input type="number" name="size_stock[{{ $shoeSize }}]" class="form-control" value="0" min="0">
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Stok -->
    <div class="form-group">
        <label>Stok</label>
        <input type="number" name="stock" class="form-control" value="{{ old('stock') }}" required>
    </div>

    <!-- Tanggal Berakhir Deal -->
    <div class="form-group">
        <label>Tanggal Berakhir Deal</label>
        <input type="date" name="deal_end_date" class="form-control" value="{{ old('deal_end_date') }}">
    </div>

    <!-- Checkbox Produk Baru, Best Seller, etc -->
    <div class="form-check">
        <input type="checkbox" name="is_new" class="form-check-input" id="is_new" {{ old('is_new') ? 'checked' : '' }}>
        <label class="form-check-label" for="is_new">Produk Baru</label>
    </div>

    <div class="form-check">
        <input type="checkbox" name="is_best_seller" class="form-check-input" id="is_best_seller" {{ old('is_best_seller') ? 'checked' : '' }}>
        <label class="form-check-label" for="is_best_seller">Best Seller</label>
    </div>

    <div class="form-check mb-3">
        <input type="checkbox" name="has_size" class="form-check-input" id="has_size" {{ old('has_size') ? 'checked' : '' }}>
        <label class="form-check-label" for="has_size">Punya Ukuran</label>
    </div>

    <div class="form-check mb-2">
        <input type="checkbox" name="is_deal" class="form-check-input" id="is_deal" {{ old('is_deal') ? 'checked' : '' }}>
        <label class="form-check-label" for="is_deal">Tampilkan sebagai "Deal of the Week"</label>
    </div>

    <!-- Gambar -->
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

    <button type="submit" class="btn btn-primary">Simpan</button>
    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Batal</a>
</form>

<script>
   document.getElementById('size_option').addEventListener('change', function() {
    var sizeOption = this.value;
    var sizeFields = document.getElementById('size_fields');
    var sizeGroupApparel = document.getElementById('size_group_apparel');
    var sizeGroupShoes = document.getElementById('size_group_shoes');

    // Hilangkan logika penyembunyian, langsung tampilkan ukuran
    if (sizeOption === 'apparel') {
        sizeFields.style.display = 'block';
        sizeGroupApparel.style.display = 'block'; // Menampilkan ukuran baju
    } else if (sizeOption === 'shoes') {
        sizeFields.style.display = 'block';
        sizeGroupShoes.style.display = 'block'; // Menampilkan ukuran sepatu
    }
});

</script>

@endsection
