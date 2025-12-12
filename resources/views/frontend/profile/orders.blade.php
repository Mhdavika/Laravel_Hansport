@extends('layouts.frontend')

@section('title', 'Profile - Orders')

@push('styles')
<link rel="stylesheet" href="{{ asset('frontend/styles/profile_styles.css') }}">
<link rel="stylesheet" href="{{ asset('frontend/styles/profile_orders.css') }}">
@endpush

@section('content')
<div class="profile-page">
    @include('layouts.breadcrumbs')

    <div class="container mt-5">
        <div class="profile-wrapper">
            
            <div class="profile-sidebar">
                @include('layouts.sidebar')
            </div>

            <div class="profile-main">
                <div class="card p-4 no-border">

                    <h4 class="mb-4">Riwayat Pesanan</h4>

                    @forelse ($orders as $order)
                        @php 
                            $firstItem = $order->items->first(); 
                        @endphp 
                        <table class="table table-bordered table-hover text-center">
                            <thead class="table-light">
                                <tr>
                                    <th>Produk</th>
                                    <th>Jumlah</th>
                                    <th>Total Harga</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                    <th>Detail</th> <!-- Column for the detail link -->
                                </tr>
                            </thead>

                            <tbody>
                                <tr>
                                    <td>
                                        @if($firstItem && $firstItem->product)
                                            <img src="{{ asset('storage/' . $firstItem->product->image) }}" alt="{{ $firstItem->product->name }}" width="60"><br>
                                            <a href="{{ route('order.detail', $order->id) }}" data-bs-toggle="modal" class="product-link" 
                                               data-order="{{ json_encode($order) }}" 
                                               data-product="{{ json_encode($firstItem->product) }}" 
                                               data-size="{{ $firstItem->size ?? '-' }}">
                                                {{ $firstItem->product->name }}
                                            </a><br>
                                            <small>Ukuran: {{ $firstItem->size ?? '-' }}</small>
                                        @else
                                            <img src="{{ asset('frontend/images/no-image.png') }}" alt="Produk tidak ditemukan" width="60"><br>
                                            <small>Produk tidak tersedia</small>
                                        @endif
                                    </td>

                                    <td>{{ $order->items->sum('quantity') }}</td> 

                                    <td>Rp{{ number_format($order->total_price, 0, ',', '.') }}</td> 

                                    <td>{{ $order->created_at->format('d M Y') }}</td> 

                                    <td>
                                        <span class="badge bg-{{ $order->status === 'completed' ? 'success' : 'warning' }}">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>

                                    <td>
                                        <!-- Button untuk membuka modal -->
                                        <button class="btn btn-info btn-sm" 
                                                data-bs-toggle="modal" data-bs-target="#detailModal"
                                                data-order="{{ json_encode($order) }}" 
                                                data-product="{{ json_encode($firstItem->product) }}" 
                                                data-size="{{ $firstItem->size ?? '-' }}">
                                            <a href="{{ route('order.detail', $order->id) }}" class="btn btn-info">Lihat Detail</a>


                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    @empty
                        <div class="alert alert-info">
                            Belum ada barang yang dipesan.
                        </div>
                    @endforelse

                </div>
            </div>

        </div>
    </div>
</div>

<!-- Modal untuk Menampilkan Detail Pesanan -->
<div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailModalLabel">Detail Pesanan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Detail Pesanan akan ditampilkan disini -->
                <div id="orderDetail">
                    <h5 id="productName"></h5>
                    <img src="" alt="" id="productImage" width="100%">
                    <p id="productSize"></p>
                    <p id="orderQuantity"></p>
                    <p id="orderTotalPrice"></p>
                    <p id="orderDate"></p>
                    <p id="orderStatus"></p>

                    <h6>Informasi Pemesan</h6>
                    <p id="customerName"></p>
                    <p id="customerEmail"></p>
                    <p id="customerPhone"></p>
                    <p id="customerPostalCode"></p>
                    <p id="customerProvince"></p>
                    <p id="customerCity"></p>
                    <p id="customerDistrict"></p>
                    <p id="customerAddress"></p>
                    <p id="shippingCourier"></p>
                    <p id="paymentMethod"></p>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

push('s@cripts')
<script>
    // Menangani klik pada produk untuk menampilkan detail pesanan di modal
    document.querySelectorAll('.product-link').forEach(function (link) {
        link.addEventListener('click', function (event) {
            var order = JSON.parse(this.getAttribute('data-order'));
            var product = JSON.parse(this.getAttribute('data-product'));
            var size = this.getAttribute('data-size');

            // Update informasi di modal
            document.getElementById('productName').textContent = product.name;
            document.getElementById('productSize').textContent = 'Ukuran: ' + size;
            document.getElementById('productImage').src = "{{ asset('storage/') }}/" + product.image;
            document.getElementById('orderQuantity').textContent = 'Jumlah: ' + order.items.sum('quantity');
            document.getElementById('orderTotalPrice').textContent = 'Total Harga: Rp' + new Intl.NumberFormat().format(order.total_price);
            document.getElementById('orderDate').textContent = 'Tanggal: ' + order.created_at;
            document.getElementById('orderStatus').textContent = 'Status: ' + order.status;

            // Informasi pemesan
            document.getElementById('customerName').textContent = 'Nama: ' + order.name;
            document.getElementById('customerEmail').textContent = 'Email: ' + order.email;
            document.getElementById('customerPhone').textContent = 'Telepon: ' + order.phone;
            document.getElementById('customerPostalCode').textContent = 'Kode Pos: ' + order.postal_code;
            document.getElementById('customerProvince').textContent = 'Provinsi: ' + order.province;
            document.getElementById('customerCity').textContent = 'Kota: ' + order.city;
            document.getElementById('customerDistrict').textContent = 'Kecamatan: ' + order.district;
            document.getElementById('customerAddress').textContent = 'Alamat: ' + order.address;
            document.getElementById('shippingCourier').textContent = 'Jasa Pengiriman: ' + order.shipping_courier;
            document.getElementById('paymentMethod').textContent = 'Metode Pembayaran: ' + order.payment_method;
        });
    });
</script>
@endpush
