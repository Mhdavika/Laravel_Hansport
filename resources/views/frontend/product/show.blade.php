@extends('layouts.frontend')

@section('title', $product->name)

@section('content')
<div class="container py-5">
    <div class="row">
        <!-- Gambar Produk -->
        <div class="col-md-6">
            <div class="product-images">
                <!-- Gambar Utama -->
                <img src="{{ asset('storage/'.$product->image) }}" alt="{{ $product->name }}" class="img-fluid">

                <div class="product-thumbnails">
                    @if($product->desc_image_1)
                    <img src="{{ asset('storage/'.$product->desc_image_1) }}" alt="Description Image 1" class="img-thumbnail">
                    @endif
                    @if($product->desc_image_2)
                    <img src="{{ asset('storage/'.$product->desc_image_2) }}" alt="Description Image 2" class="img-thumbnail">
                    @endif
                    @if($product->desc_image_3)
                    <img src="{{ asset('storage/'.$product->desc_image_3) }}" alt="Description Image 3" class="img-thumbnail">
                    @endif
                </div>
            </div>
        </div>

        <!-- Detail Produk -->
        <div class="col-md-6">
            <h1>{{ $product->name }}</h1>
            <p><strong>Harga: </strong>Rp. {{ number_format($product->price, 0, ',', '.') }}</p>

            @if($product->discount_price)
            <p><strong>Harga Diskon: </strong>Rp. {{ number_format($product->discount_price, 0, ',', '.') }}</p>
            @endif

            <p><strong>Deskripsi:</strong></p>
            <p>{{ $product->description }}</p>

         @if($product->has_size)
    <div class="form-group">
        <label>Pilih Ukuran:</label>
        <div class="d-flex">
            @foreach(explode(',', $product->size_options) as $size)
                <button type="button" class="btn btn-outline-primary size-option" data-size="{{ $size }}">
                    {{ strtoupper($size) }}
                </button>
            @endforeach
        </div>
    </div>
@else
    <p>Ukuran tidak tersedia untuk produk ini.</p>
@endif

            <p><strong>Stok:</strong> {{ $product->stock }}</p>

           <!-- Formulir untuk menambah ke keranjang -->
<form action="{{ route('cart.store') }}" method="POST" class="d-inline-block">
    @csrf
    <input type="hidden" name="product_id" value="{{ $product->id }}">
    <input type="hidden" name="size" id="size" value="{{ old('size') }}"> <!-- Menyimpan ukuran yang dipilih -->
    <input type="number" name="quantity" value="1" min="1" class="form-control" style="width: 80px;" required>
    <button type="submit" class="btn btn-primary">Tambahkan ke Keranjang</button>
</form>
 {{-- Sekarang --> --}}
            <form action="{{ route('checkout.index') }}" method="GET" class="d-inline-block">
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <input type="hidden" name="size" id="size-checkout" value="{{ old('size') }}">
                <input type="number" name="quantity" value="1" min="1" class="form-control" style="width: 80px;" required>
                <button type="submit" class="btn btn-success">Beli Sekarang</button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
  // Menambahkan event listener untuk setiap tombol ukuran
document.querySelectorAll('.size-option').forEach(button => {
    button.addEventListener('click', function() {
        // Menambahkan dan menghapus kelas 'selected' untuk memberi feedback visual
        document.querySelectorAll('.size-option').forEach(btn => {
            btn.classList.remove('selected'); // Menghapus kelas 'selected' dari semua tombol
        });

        this.classList.add('selected'); // Menambahkan kelas 'selected' ke tombol yang dipilih

        // Menyimpan ukuran yang dipilih ke input tersembunyi
        const size = this.getAttribute('data-size');
        document.getElementById('size').value = size;
    });
});

</script>
@endpush
