@extends('layouts.frontend')

@section('title', 'Detail Pesanan')

@push('styles')
<style>
    .badge-status {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        color: #fff;
    }
    .bg-pending { background-color: #ffc107; }
    .bg-paid { background-color: #28a745; }
    .bg-expired { background-color: #dc3545; }
</style>
@endpush

@section('content')
<div class="container py-5">

    <h2 class="mb-3">Detail Pesanan #{{ $order->id }}</h2>

    @php
        $status = $order->status;
        $badgeClass =
            $status === 'pending' ? 'bg-pending' :
            ($status === 'expired' ? 'bg-expired' : 'bg-paid');
    @endphp

    <div class="mb-3">
        <span class="badge-status {{ $badgeClass }}">
            {{ strtoupper($order->status) }}
        </span>
    </div>

    <div class="row">
        {{-- Info Pesanan --}}
        <div class="col-md-6 mb-4">
            <div class="card p-3">
                <h5>Info Pesanan</h5>
                <p class="mb-1"><strong>Tanggal Pesanan:</strong> {{ $order->created_at->format('d M Y H:i') }}</p>

                @if($order->expires_at)
                    <p class="mb-1">
                        <strong>Batas Pembayaran:</strong>
                        {{ \Carbon\Carbon::parse($order->expires_at)->format('d M Y H:i') }}
                    </p>
                @endif

                <p class="mb-1"><strong>Metode Pembayaran:</strong> {{ strtoupper($order->payment_method) }}</p>
                <p class="mb-1">
                    <strong>Total:</strong>
                    Rp{{ number_format($order->total_price, 0, ',', '.') }}
                </p>

                @if($order->proof_file)
                    <p class="mb-1"><strong>Bukti Pembayaran:</strong></p>
                    <img src="{{ asset('storage/'.$order->proof_file) }}" alt="Bukti pembayaran" style="max-width: 100%; border-radius: 8px;">
                @endif
            </div>
        </div>

        {{-- Alamat Pengiriman --}}
        <div class="col-md-6 mb-4">
            <div class="card p-3">
                <h5>Alamat Pengiriman</h5>
                <p class="mb-1"><strong>Nama:</strong> {{ $order->name }}</p>
                <p class="mb-1"><strong>Telepon:</strong> {{ $order->phone }}</p>
                <p class="mb-1"><strong>Email:</strong> {{ $order->email }}</p>
                <p class="mb-1">
                    <strong>Alamat Lengkap:</strong><br>
                    {{ $order->address }}<br>
                    {{ $order->district }}, {{ $order->city }}, {{ $order->province }} - {{ $order->postal_code }}
                </p>
            </div>
        </div>
    </div>

    {{-- Item Pesanan --}}
    <div class="card p-3">
        <h5 class="mb-3">Item Pesanan</h5>

        @if($order->items && $order->items->count())
            <div class="table-responsive">
                <table class="table table-bordered table-sm align-middle">
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th>Ukuran</th>
                            <th>Jumlah</th>
                            <th>Harga Satuan</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                            <tr>
                                <td>{{ $item->product_name ?? ($item->product->name ?? 'Produk') }}</td>
                                <td>{{ $item->size ?? '-' }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>Rp{{ number_format($item->price, 0, ',', '.') }}</td>
                                <td>Rp{{ number_format($item->subtotal, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-muted">Tidak ada item pada pesanan ini.</p>
        @endif
    </div>

    <div class="mt-3">
        <a href="{{ route('profile.orders') }}" class="btn btn-outline-secondary">
            &laquo; Kembali ke Riwayat Pesanan
        </a>
        <a href="{{ route('homepage') }}" class="btn btn-dark">
            Ke Beranda
        </a>
    </div>

</div>
@endsection
