@extends('layouts.frontend')

@section('title', 'Riwayat Pesanan')

@push('styles')
<style>
    .order-card {
        border: 1px solid #ddd;
        border-radius: 10px;
        padding: 16px;
        margin-bottom: 16px;
    }
    .badge-status {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        color: white;
    }
    .bg-pending { background-color: #ffc107; }
    .bg-paid { background-color: #28a745; }
    .bg-expired { background-color: #dc3545; }
</style>
@endpush

@section('content')
<div class="container py-5">
    
    <h2 class="mb-4">Riwayat Pesanan</h2>

    @if($orders->isEmpty())
        <div class="alert alert-info text-center">
            Belum ada riwayat pesanan.
        </div>
    @endif

    @foreach ($orders as $order)
        <div class="order-card">

            <div class="d-flex justify-content-between">
                <h5 class="mb-1">Pesanan #{{ $order->id }}</h5>

                {{-- Status warna --}}
                @php
                    $status = $order->status;
                    $badgeClass =
                        $status === 'pending' ? 'bg-pending' :
                        ($status === 'expired' ? 'bg-expired' : 'bg-paid');
                @endphp

                <span class="badge-status {{ $badgeClass }}">
                    {{ strtoupper($order->status) }}
                </span>
            </div>

            <p class="mb-1">
                <strong>Tanggal:</strong>
                {{ $order->created_at->format('d M Y H:i') }}
            </p>

            {{-- Batas waktu pembayaran --}}
            @if($order->expires_at)
                <p class="mb-1">
                    <strong>Batas Pembayaran:</strong>
                    {{ \Carbon\Carbon::parse($order->expires_at)->format('d M Y H:i') }}
                </p>
            @endif

            <p class="mb-1">
                <strong>Metode Pembayaran:</strong>
                {{ strtoupper($order->payment_method) }}
            </p>

            <p class="mb-2">
                <strong>Total:</strong> 
                Rp{{ number_format($order->total_price, 0, ',', '.') }}
            </p>

            <a href="{{ route('profile.order.detail', $order->id ?? '#') }}" 
               class="btn btn-dark btn-sm mt-2">
                Lihat Detail
            </a>

        </div>
    @endforeach

</div>
@endsection
