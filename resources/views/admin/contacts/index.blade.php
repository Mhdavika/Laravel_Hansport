@extends('layouts.backend')

@section('title', 'Pesan dari Pengunjung')

@section('content')
<h1 class="h3 mb-4 text-gray-800">Pesan dari Pengunjung</h1>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Nama</th>
            <th>Email</th>
            <th>Nomor WhatsApp</th>
            <th>Pesan</th>
            <th>Tanggal</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($messages as $msg)
        <tr>
            <td>{{ $msg->name }}</td>
            <td>{{ $msg->email }}</td>
            <td>{{ $msg->whatsapp }}</td> 
            <td>{{ $msg->message }}</td>
            <td>{{ $msg->created_at->format('d M Y H:i') }}</td>
        </tr>
        @empty
        <tr>
            <td colspan="5" class="text-center">Belum ada pesan masuk.</td>
        </tr>
        @endforelse
    </tbody>
</table>
@endsection
