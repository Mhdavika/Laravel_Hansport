@extends('layouts.frontend')

@section('title', 'Detail Pesanan')

@push('styles')
<link rel="stylesheet" href="{{ asset('frontend/styles/order_detail.css') }}">
@endpush

@section('content')
<div class="container py-5">

    <h2>Detail Pesanan</h2>

    <!-- Informasi Produk -->
    <div class="card p-4 mb-4">
        <h5>Produk yang Dipesan</h5>
        @foreach ($order->items as $item)
            <div class="d-flex justify-content-between mb-2">
                <div class="d-flex">
                    <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}" width="100" class="me-3">
                    <div>
                        <strong>{{ $item->product->name }}</strong><br>
                        <small>Ukuran: {{ $item->size ?? '-' }}</small><br>
                        <small>Jumlah: {{ $item->quantity }}</small><br>
                        <strong>Total Harga: Rp{{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}</strong>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Informasi Pengiriman -->
    <div class="card p-4 mb-4">
        <h5>Informasi Pengiriman</h5>
        <p><strong>Nama Pemesan: </strong>{{ $order->name }}</p>
        <p><strong>Email: </strong>{{ $order->email }}</p>
        <p><strong>Telepon: </strong>{{ $order->phone }}</p>
        <p><strong>Kode Pos: </strong>{{ $order->postal_code }}</p>
        <p><strong>Provinsi: </strong>{{ $order->province }}</p>
        <p><strong>Kota: </strong>{{ $order->city }}</p>
        <p><strong>Kecamatan: </strong>{{ $order->district }}</p>
        <p><strong>Alamat: </strong>{{ $order->address }}</p>
    </div>

    <!-- Informasi Pembayaran -->
    <div class="card p-4 mb-4">
        <h5>Informasi Pembayaran</h5>
        <p><strong>Metode Pembayaran: </strong>{{ ucfirst($order->payment_method) }}</p>
        <p><strong>Jasa Pengiriman: </strong>{{ $order->shipping_courier }}</p>
    </div>

    <!-- Tombol Kembali -->
    <a href="{{ route('profile.orders') }}" class="btn btn-secondary">Kembali ke Riwayat Pesanan</a>

</div>
@endsection
