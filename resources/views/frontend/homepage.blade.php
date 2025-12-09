@extends('layouts.frontend')

@section('content')

<!-- Slider -->
@php
$HeroSection = asset('frontend/images/HeroSection.png');
$category1 = asset('frontend/images/cat1.png');
$category2 = asset('frontend/images/cat2.png');
$category3 = asset('frontend/images/cat3.png');
@endphp

<div class="main_slider" style="background-image:url('{{ $HeroSection }}')">
	<div class="container fill_height">
		<div class="row align-items-center fill_height">
			<div class="col">
				<div class="main_slider_content">
					<h6>Koleksi Terbaru 2025</h6>
					<h1>Diskon 30% untuk Pengguna Baru</h1>
					<div class="red_button shop_now_button"><a href="#">Belanja</a></div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Banner -->

<div class="banner">
	<div class="container">
		<div class="row">
			<div class="col-md-4">
				<div class="banner_item align-items-center" style="background-image:url('{{ $category1 }}')">
					<div class="banner_category">
						<a href="{{ route('shop.index', ['category' => 'Basket']) }}">Basket</a>
					</div>
				</div>
			</div>
			<div class="col-md-4">
				<div class="banner_item align-items-center" style="background-image:url('{{ $category2 }}')">
					<div class="banner_category">
						<a href="{{ route('shop.index', ['category' => 'Sepak Bola']) }}">Sepak Bola</a>
					</div>
				</div>
			</div>
			<div class="col-md-4">
				<div class="banner_item align-items-center" style="background-image:url('{{ $category3 }}')">
					<div class="banner_category">
						<a href="{{ route('shop.index', ['category' => 'Badminton']) }}">Badminton</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- New Arrivals -->
<div class="new_arrivals">
	<div class="container">
		<div class="row">
			<div class="col text-center">
				<div class="section_title new_arrivals_title">
					<h2>Produk Terbaru</h2>
				</div>
			</div>
		</div>

		<!-- Filter Buttons -->
		<div class="row align-items-center">
			<div class="col text-center">
				<div class="new_arrivals_sorting">
					<ul class="arrivals_grid_sorting clearfix button-group filters-button-group">
						<li class="grid_sorting_button button d-flex flex-column justify-content-center align-items-center active is-checked" data-filter="*">Semua</li>
						<li class="grid_sorting_button button d-flex flex-column justify-content-center align-items-center" data-filter=".basket">Basket</li>
						<li class="grid_sorting_button button d-flex flex-column justify-content-center align-items-center" data-filter=".sepakbola">Sepak Bola</li>
						<li class="grid_sorting_button button d-flex flex-column justify-content-center align-items-center" data-filter=".badminton">Badminton</li>
					</ul>
				</div>
			</div>
		</div>

		<!-- Product Grid -->
		<div class="row">
			<div class="col">
				<div class="product-grid" data-isotope='{ "itemSelector": ".product-item", "layoutMode": "fitRows" }'>

					@php $count = []; @endphp
					@foreach($newProducts as $product)
					@php
    $category = $product->category->name ?? 'Uncategorized';
    // Tanpa filter jumlah, tampilkan semua dulu
    $class = strtolower(str_replace(' ', '', $category));
@endphp


					<div class="col-md-4 product-item {{ $class }}">
						<div class="product {{ $product->discount_price ? 'discount' : '' }} product_filter">
							<div class="product_image">
								<img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
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

						{{-- Tombol Tambah ke Keranjang --}}
						<div class="red_button add_to_cart_button">
							<a href="#" class="btn-add-to-cart"
								data-toggle="modal"
								data-target="#addToCartModal{{ $product->id }}"
								data-stock="{{ $product->stock }}"
								data-product="{{ $product->name }}">
								Tambah ke keranjang
							</a>
						</div>

						{{-- Modal --}}
						<div class="modal fade" id="addToCartModal{{ $product->id }}" tabindex="-1" role="dialog">
							<div class="modal-dialog modal-dialog-centered" role="document">
								<div class="modal-content p-3">
									<div class="modal-header">
										<h5 class="modal-title">Pilih Ukuran & Jumlah</h5>
										<button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
									</div>
									<form action="{{ route('cart.store') }}" method="POST">
										@csrf
										<input type="hidden" name="product_id" value="{{ $product->id }}">
										<input type="hidden" class="max_stock" data-product-id="{{ $product->id }}" value="{{ $product->stock }}">

										<div class="modal-body">
											@if($product->has_size)
											<div class="mb-3">
												<label class="form-label mb-2">Pilih Ukuran:</label>
												<div class="btn-group-toggle d-flex flex-wrap gap-2" data-toggle="buttons">
													@foreach($product->size_list as $size)
													<label class="btn btn-outline-dark">
														<input type="radio" name="size" value="{{ $size }}" autocomplete="off" required> {{ $size }}
													</label>
													@endforeach
												</div>
											</div>
											@else
											<div class="text-muted mb-3">Ukuran tidak tersedia untuk produk ini</div>
											@endif

											<div class="mb-3">
												<label class="form-label d-block mb-2">Jumlah:</label>
												<div class="input-group" style="max-width: 150px;">
													<button type="button" class="btn btn-outline-secondary btn-sm btn-minus" data-id="{{ $product->id }}">-</button>
													<input type="number"
														name="quantity"
														id="qtyInput{{ $product->id }}"
														class="form-control form-control-sm text-center qty-input"
														value="1"
														min="1"
														max="{{ $product->stock }}"
														data-max="{{ $product->stock }}"
														required
														readonly>
													<button type="button" class="btn btn-outline-secondary btn-sm btn-plus" data-id="{{ $product->id }}">+</button>
												</div>
											</div>

											<div class="text-muted small">Stok tersedia: {{ $product->stock }}</div>
										</div>

										<div class="modal-footer">
											<button type="submit" class="btn btn-danger w-100">Masukkan ke Keranjang</button>
										</div>
									</form>
								</div>
							</div>
						</div>
					</div>
					@endforeach


				</div>
			</div>
		</div>

	</div>
</div>

<!-- Deal of the week -->
@if($dealProduct)
@php
$deadline = \Carbon\Carbon::parse($dealProduct->deal_end_date)->timezone('Asia/Jakarta')->format('Y-m-d\TH:i:s');
@endphp

<div class="deal_ofthe_week">
	<div class="container">
		<div class="row align-items-center">
			<!-- Gambar produk -->
			<div class="col-lg-6">
				<div class="deal_ofthe_week_img">
					<img src="{{ asset('storage/' . $dealProduct->image) }}" alt="{{ $dealProduct->name }}">
				</div>
			</div>

			<!-- Konten dan countdown -->
			<div class="col-lg-6 text-right deal_ofthe_week_col">
				<div class="deal_ofthe_week_content d-flex flex-column align-items-center float-right">
					<div class="section_title">
						<h2>{{ $dealProduct->name }}</h2>
					</div>

					<!-- Countdown -->
					<ul class="timer" id="deal-countdown" data-deadline="{{ $deadline }}">
						<li class="d-inline-flex flex-column justify-content-center align-items-center">
							<div id="days" class="timer_num">00</div>
							<div class="timer_unit">Hari</div>
						</li>
						<li class="d-inline-flex flex-column justify-content-center align-items-center">
							<div id="hours" class="timer_num">00</div>
							<div class="timer_unit">Jam</div>
						</li>
						<li class="d-inline-flex flex-column justify-content-center align-items-center">
							<div id="minutes" class="timer_num">00</div>
							<div class="timer_unit">Menit</div>
						</li>
						<li class="d-inline-flex flex-column justify-content-center align-items-center">
							<div id="seconds" class="timer_num">00</div>
							<div class="timer_unit">Detik</div>
						</li>
					</ul>

					<!-- Harga -->
					<div class="product_price mt-3">
						Rp{{ number_format($dealProduct->discount_price ?? $dealProduct->price, 0, ',', '.') }}
						@if($dealProduct->discount_price)
						<span>Rp{{ number_format($dealProduct->price, 0, ',', '.') }}</span>
						@endif
					</div>

					<div class="red_button deal_ofthe_week_button mt-3">
						<a href="{{ route('shop.index') }}">Belanja</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endif

<!-- Best Sellers -->
<div class="best_sellers">
	<div class="container">
		<div class="row">
			<div class="col text-center">
				<div class="section_title new_arrivals_title">
					<h2>Produk Unggulan</h2>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col">
				<div class="product_slider_container">
					<div class="owl-carousel owl-theme product_slider">

						<!-- Slide 1 -->

						@foreach ($bestSellers as $product)
						<div class="owl-item product_slider_item">
							<div class="product-item">
								<div class="product {{ $product->discount_price ? 'discount' : '' }}">
									<div class="product_image">
										<img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
									</div>
									<div class="favorite favorite_left"></div>

									@if ($product->discount_price)
									<div class="product_bubble product_bubble_right product_bubble_red d-flex flex-column align-items-center">
										<span>-{{ number_format((($product->price - $product->discount_price)/$product->price)*100, 0) }}%</span>
									</div>
									@endif

									<div class="product_info">
										<h6 class="product_name">
											<a href="{{ route('product.show', $product->id) }}">{{ $product->name }}</a>
										</h6>
										<div class="product_price">
											@if ($product->discount_price)
											Rp{{ number_format($product->discount_price, 0, ',', '.') }}
											<span>Rp{{ number_format($product->price, 0, ',', '.') }}</span>
											@else
											Rp{{ number_format($product->price, 0, ',', '.') }}
											@endif
										</div>
									</div>
								</div>
							</div>
						</div>
						@endforeach

					</div>

					<!-- Slider Navigation -->

					<div class="product_slider_nav_left product_slider_nav d-flex align-items-center justify-content-center flex-column">
						<i class="fa fa-chevron-left" aria-hidden="true"></i>
					</div>
					<div class="product_slider_nav_right product_slider_nav d-flex align-items-center justify-content-center flex-column">
						<i class="fa fa-chevron-right" aria-hidden="true"></i>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Benefit -->

@include('layouts.benefit')


@endsection

@push('scripts')
<script src="{{ asset('frontend/js/homepage_custom.js') }}"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
@endpush