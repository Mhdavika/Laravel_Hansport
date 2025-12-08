@extends('layouts.backend')
@section('title', 'Semua Pesanan')

@section('content')
<h1 class="h3 mb-4 text-gray-800">Semua Pesanan</h1>

<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>Nama Pemesan</th>
            <th>Metode</th>
            <th>Bank / E-Wallet</th>
            <th>Bukti Transfer</th>
            <th>Total</th>
            <th>Status</th>
            <th>Tanggal</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($orders as $order)
        <tr>
            <td>{{ $order->name }}</td>

            <td>
                <span class="badge badge-{{ $order->payment_method == 'cod' ? 'secondary' : 'info' }}">
                    {{ ucfirst($order->payment_method) }}
                </span>
            </td>

            <td>
                @if($order->payment_method == 'transfer')
                    <strong>{{ strtoupper($order->bank_name) }}</strong>
                @elseif($order->payment_method == 'ewallet')
                    <strong>{{ strtoupper($order->ewallet_name) }}</strong>
                @else
                    <span class="text-muted">-</span>
                @endif
            </td>

            <td>
                @if($order->proof_file)
                    <a href="{{ asset('storage/' . $order->proof_file) }}" target="_blank" class="btn btn-sm btn-outline-primary">Lihat</a>
                @else
                    <span class="text-muted">-</span>
                @endif
            </td>

            <td>Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>

            <td>
                @php
                    $badge = match($order->status) {
                        'pending' => 'warning',
                        'confirmed' => 'primary',
                        'proses' => 'info',
                        'dikirim' => 'secondary',
                        'selesai' => 'success',
                        'dibatalkan' => 'danger',
                        default => 'light'
                    };
                @endphp
                <span class="badge badge-{{ $badge }}">{{ ucfirst($order->status) }}</span>
            </td>

            <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>

            <td>
                <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-info">
                    Detail
                </a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
