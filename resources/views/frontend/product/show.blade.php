@extends('layouts.frontend')

@section('title', 'Detail Product')

@push('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('frontend/styles/bootstrap4/bootstrap.min.css') }}">
<link href="{{ asset('frontend/plugins/font-awesome-4.7.0/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="{{ asset('frontend/plugins/OwlCarousel2-2.2.1/owl.carousel.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('frontend/plugins/OwlCarousel2-2.2.1/owl.theme.default.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('frontend/plugins/OwlCarousel2-2.2.1/animate.css') }}">
<link rel="stylesheet" href="{{ asset('frontend/plugins/themify-icons/themify-icons.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('frontend/plugins/jquery-ui-1.12.1.custom/jquery-ui.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('frontend/styles/single_styles.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('frontend/styles/single_responsive.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('frontend/styles/show_style.css') }}">
@endpush

@section('content')
<div class="container single_product_container">
    @include('layouts.breadcrumbs')

    <div class="row">
        <!-- Gambar Produk -->
        @php
        $BgGambarProduct = asset('storage/' . $product->image);
        @endphp
        <div class="col-lg-7">
            <div class="single_product_pics">
                <div class="row">
                    <div class="col-lg-3 thumbnails_col order-lg-1 order-2">
                        <div class="single_product_thumbnails">
                            <ul>
                                @foreach([$product->desc_image_1, $product->desc_image_2, $product->desc_image_3] as $thumb)
                                @if($thumb)
                                <li><img src="{{ asset('storage/' . $thumb) }}" alt="" data-image="{{ asset('storage/' . $thumb) }}"></li>
                                @endif
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-9 image_col order-lg-2 order-1">
                        <div class="single_product_image">
                            <div class="single_product_image_background" style="background-image:url('{{ $BgGambarProduct }}')"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detail Produk -->
        <div class="col-lg-5">
            <div class="product_details">
                <div class="product_details_title">
                    <h2>{{ $product->name }}</h2>
                    <p>{{ $product->description }}</p>
                </div>

                <div class="free_delivery d-flex flex-row align-items-center justify-content-center mb-4">
                    <span class="ti-truck"></span><span>Gratis Ongkos Kirim</span>
                </div>

                <!-- Pilih Ukuran -->
                <div class="form-group mb-3 d-flex align-items-center">
                    <label for="size" class="mb-0 me-3" style="font-weight: normal; min-width: 100px;">Pilih Ukuran:</label>
                    <div class="size-options d-flex">
                        @foreach(explode(',', $product->size_options) as $size)
                        <input type="radio" id="size-{{ $size }}" name="size" value="{{ $size }}">
                        <label for="size-{{ $size }}">{{ $size }}</label>
                        @endforeach
                    </div>
                </div>

                <!-- Info Stok -->
                <div class="mb-3 d-flex align-items-center">
                    <label class="mb-0 me-3" style="font-weight: normal; min-width: 100px;">Stok:</label>
                    <span>{{ $product->stock }}</span> <!-- kamu bisa ganti ini dengan angka statis apa pun -->
                </div>

                @if($product->discount_price)
                <div class="original_price">Rp{{ number_format($product->price, 0, ',', '.') }}</div>
                <div class="product_price">Rp{{ number_format($product->discount_price, 0, ',', '.') }}</div>
                @else
                <div class="product_price">Rp{{ number_format($product->price, 0, ',', '.') }}</div>
                @endif

                <!-- 🛒 Tombol Add to Cart -->
                <form action="{{ route('cart.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="quantity" id="quantity_input" value="1">
                    <input type="hidden" name="size" id="selected_size" required>

                    <div class="quantity d-flex flex-column flex-sm-row align-items-sm-center">
                        <span>Jumlah:</span>
                        <div class="quantity_selector">
                            <span class="minus"><i class="fa fa-minus" aria-hidden="true"></i></span>
                            <span id="quantity_value">1</span>
                            <span class="plus"><i class="fa fa-plus" aria-hidden="true"></i></span>
                        </div>
                        <input type="hidden" id="max_stock" value="{{ $product->stock }}">
                        <div class="red_button add_to_cart_button ms-sm-3 mt-3 mt-sm-0">
                            <button type="submit" class="btn text-white border-0 p-0 bg-transparent">Tambah Keranjang</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('frontend/js/jquery-3.2.1.min.js') }}"></script>
<script src="{{ asset('frontend/styles/bootstrap4/popper.js') }}"></script>
<script src="{{ asset('frontend/styles/bootstrap4/bootstrap.min.js') }}"></script>
<script src="{{ asset('frontend/plugins/Isotope/isotope.pkgd.min.js') }}"></script>
<script src="{{ asset('frontend/plugins/OwlCarousel2-2.2.1/owl.carousel.js') }}"></script>
<script src="{{ asset('frontend/plugins/easing/easing.js') }}"></script>
<script src="{{ asset('frontend/plugins/jquery-ui-1.12.1.custom/jquery-ui.js') }}"></script>
<script src="{{ asset('frontend/js/single_custom.js') }}"></script>
@endpush