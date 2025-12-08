@extends('layouts.frontend')

@section('title', 'Pesanan Berhasil')

@push('styles')
<link rel="stylesheet" href="{{ asset('frontend/styles/confirm.css') }}">
@endpush

@section('content')
<div class="container py-5 text-center">

    {{-- Judul utama --}}
    <h2 class="mt-4">Pesanan Berhasil!</h2>

    {{-- Konten berdasarkan metode pembayaran --}}
    @php
        $payment = session('payment_method');
    @endphp

    @if($payment === 'transfer')
        <p class="lead">Bukti pembayaranmu telah kami terima dan sedang diverifikasi.</p>
    @elseif($payment === 'ewallet')
        <p class="lead">Pembayaran via e-wallet berhasil dikirim. Tim kami akan segera memverifikasi.</p>
    @elseif($payment === 'cod')
        <p class="lead">Pesanan COD kamu telah dikonfirmasi dan akan segera diproses.</p>
    @else
        <p class="lead">Terima kasih atas pesananmu. Detail pesanan telah kami terima.</p>
    @endif

    <a href="{{ url('/homepage') }}" class="btn btn-dark mt-3">Kembali ke Beranda</a>
</div>
@endsection
