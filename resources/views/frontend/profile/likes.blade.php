@extends('layouts.frontend')

@section('title', 'Profile - Likes')

@push('styles')
<link rel="stylesheet" href="{{ asset('frontend/styles/profile_styles.css') }}">
<link rel="stylesheet" href="{{ asset('frontend/plugins/font-awesome-4.7.0/css/font-awesome.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('frontend/styles/bootstrap4/bootstrap.min.css') }}">
<link href="{{ asset('frontend/plugins/font-awesome-4.7.0/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="{{ asset('frontend/plugins/OwlCarousel2-2.2.1/owl.carousel.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('frontend/plugins/OwlCarousel2-2.2.1/owl.theme.default.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('frontend/plugins/OwlCarousel2-2.2.1/animate.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('frontend/styles/main_styles.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('frontend/styles/responsive.css') }}">
@endpush

@section('content')
<div class="profile-page">
    @include('layouts.breadcrumbs')

    <div class="container mt-5">
        <div class="profile-wrapper">
            <!-- Sidebar -->
            <div class="profile-sidebar">
                @include('layouts.sidebar')
            </div>

            <!-- Main content -->
            <div class="profile-main">
                <h4 class="mb-4">Produk yang Anda Sukai</h4>

                <div class="row">
                    <div class="col">
                        <div class="product-grid" data-isotope='{ "itemSelector": ".product-item", "layoutMode": "fitRows" }'>

                            @forelse ($products as $product)
                            <div class="product-item">
                                <div class="product discount product_filter">

                                    <div class="product_image">
                                  <img src="{{ asset('storage/'.$product->image) }}" alt="{{ $product->name }}">

                                    </div>

                                    <div class="favorite favorite_left">
                                        <form action="{{ route('like.toggle', $product->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" style="background: none; border: none; padding: 0;">
                                                @if(auth()->check() && auth()->user()->likes->contains('product_id', $product->id))
                                                    <i class="fa fa-heart text-danger"></i>
                                                @else
                                                    <i class="fa fa-heart-o text-muted"></i>
                                                @endif
                                            </button>
                                        </form>
                                    </div>

                                    @if($product->discount_price)
                                    <div class="product_bubble product_bubble_right product_bubble_red d-flex flex-column align-items-center">
                                        <span>
                                            -{{ round((($product->price - $product->discount_price) / $product->price) * 100) }}%
                                        </span>
                                    </div>
                                    @endif

                                    <div class="product_info">
                                        <h6 class="product_name">
                                            <a href="{{ route('product.show', $product->id) }}">{{ $product->name }}</a>
                                        </h6>
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
                            @empty
                            <div class="col-12 text-center text-muted">
                                Belum ada produk yang disukai.
                            </div>
                            @endforelse

                        </div>
                    </div>
                </div>

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
<script src="{{ asset('frontend/js/custom.js') }}"></script>
@endpush