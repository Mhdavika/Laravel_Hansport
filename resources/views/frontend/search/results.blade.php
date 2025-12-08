@extends('layouts.frontend')

@section('title', 'Hasil Pencarian')

@section('content')
<div class="container mt-5 mb-5" style="padding-top: 170px;">
    <h4>Hasil pencarian untuk: <strong>{{ $query }}</strong></h4>

    @if($results->count() > 0)
    <div class="row">
        <div class="col">
            <div class="product-grid" data-isotope='{ "itemSelector": ".product-item", "layoutMode": "fitRows" }'>
                @foreach($results as $product)
                @php
                    $category = $product->category->name ?? 'umum';
                    $class = strtolower(str_replace(' ', '', $category));
                @endphp

                <div class="product-item {{ $class }}" style="width: 230px;">
                    <div class="product discount product_filter">
                        <div class="product_image">
                            <img src="{{ asset( $product->image) }}" alt="{{ $product->name }}">
                        </div>
                        <div class="favorite favorite_left"></div>

                        @if($product->discount_price)
                        <div class="product_bubble product_bubble_right product_bubble_red d-flex flex-column align-items-center">
                            <span>
                                -{{ round((($product->price - $product->discount_price) / $product->price) * 100) }}%
                            </span>
                        </div>
                        @endif

                        <div class="product_info">
                            <h6 class="product_name"><a href="#">{{ $product->name }}</a></h6>
                            <div class="product_price">
                                Rp{{ number_format($product->discount_price ?? $product->price, 0, ',', '.') }}
                                @if($product->discount_price)
                                <span>Rp{{ number_format($product->price, 0, ',', '.') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="red_button add_to_cart_button"><a href="#">Tambah ke keranjang</a></div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @else
    <p class="mt-3">Tidak ada produk ditemukan.</p>
    @endif
</div>
@endsection
