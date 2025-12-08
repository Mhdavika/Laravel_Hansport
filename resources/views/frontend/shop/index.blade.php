@extends('layouts.frontend')

@push('styles')
<!-- shop -->
<link rel="stylesheet" type="text/css" href="{{ asset('frontend/plugins/jquery-ui-1.12.1.custom/jquery-ui.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('frontend/styles/categories_styles.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('frontend/styles/categories_responsive.css') }}">
@endpush

@section('content')

<div class="container product_section_container">
	<div class="row">
		<div class="col product_section clearfix">

			<!-- Breadcrumbs -->
			@include('layouts.breadcrumbs')

			<!-- Sidebar -->

			<div class="sidebar">
				<div class="sidebar_section">
					<div class="sidebar_title">
						<h5>Kategori Produk</h5>
					</div>
					<ul class="sidebar_categories">

						{{-- Kategori Utama --}}
						@foreach(['Basket', 'Sepak Bola', 'Badminton'] as $cat)
						<li class="{{ request('category') == $cat ? 'active' : '' }}">
							<a href="{{ route('shop.index', ['category' => $cat]) }}">
								@if(request('category') == $cat)
								<span><i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
								@endif
								{{ $cat }}
							</a>
						</li>
						@endforeach

						{{-- Produk Khusus --}}
						<li class="{{ request('category') == 'Produk Terbaru' ? 'active' : '' }}">
							<a href="{{ route('shop.index', ['category' => 'Produk Terbaru']) }}">
								@if(request('category') == 'Produk Terbaru')
								<span><i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
								@endif
								Produk Terbaru
							</a>
						</li>

						<li class="{{ request('category') == 'Produk Unggulan' ? 'active' : '' }}">
							<a href="{{ route('shop.index', ['category' => 'Produk Unggulan']) }}">
								@if(request('category') == 'Produk Unggulan')
								<span><i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
								@endif
								Produk Unggulan
							</a>
						</li>
					</ul>
				</div>

				<!-- Price Range Filtering -->
				<div class="sidebar_section">
					<div class="sidebar_title">
						<h5>Filter Harga</h5>
					</div>

					<p>
						<input type="text" id="amount" readonly style="border:0; color:#f6931f; font-weight:bold;">
					</p>

					<div id="slider-range"></div>

					<form method="GET" action="{{ route('shop.index') }}">
						<input type="hidden" id="min_price" name="min_price">
						<input type="hidden" id="max_price" name="max_price">

						{{-- Tambahkan kembali kategori jika sedang di-filter --}}
						@if(request('category'))
						<input type="hidden" name="category" value="{{ request('category') }}">
						@endif

						{{-- Tombol submit --}}
						<button type="submit" class="filter_button" style="margin-top: 15px;"><span>Filter</span></button>
					</form>
				</div>


			</div>

			<!-- Main Content -->

			<div class="main_content">

				<!-- Products -->

				<div class="products_iso">
					<div class="row">
						<div class="col">

							<!-- Product Grid -->
							<div class="product-grid">

								@foreach ($products as $product)
								<div class="product-item">
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

										{{-- Bubble Diskon --}}
										@if ($product->discount_price)
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
												@if ($product->discount_price)
												<span>Rp{{ number_format($product->price, 0, ',', '.') }}</span>
												@endif
											</div>
										</div>
									</div>
									<div class="red_button add_to_cart_button">
										<a href="#"
											class="btn-add-to-cart"
											data-toggle="modal"
											data-target="#addToCartModal{{ $product->id }}"
											data-stock="{{ $product->stock }}"
											data-product="{{ $product->name }}">
											Tambah ke keranjang
										</a>
									</div>

									<!-- Modal -->
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
														{{-- Ukuran --}}
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

														{{-- Jumlah --}}
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
		</div>
	</div>
</div>

<!-- Benefit -->
@include('layouts.benefit')

@endsection

@push('scripts')
<!-- shop -->
<script src="{{ asset('frontend/js/jquery-3.2.1.min.js') }}"></script>
<script src="{{ asset('frontend/styles/bootstrap4/popper.js') }}"></script>
<script src="{{ asset('frontend/styles/bootstrap4/bootstrap.min.js') }}"></script>
<script src="{{ asset('frontend/plugins/Isotope/isotope.pkgd.min.js') }}"></script>
<script src="{{ asset('frontend/plugins/OwlCarousel2-2.2.1/owl.carousel.js') }}"></script>
<script src="{{ asset('frontend/plugins/easing/easing.js') }}"></script>
<script src="{{ asset('frontend/plugins/jquery-ui-1.12.1.custom/jquery-ui.js') }}"></script>
<script src="{{ asset('frontend/js/categories_custom.js') }}"></script>
<script src="{{ asset('frontend/js/shop_custom.js') }}"></script>

@endpush