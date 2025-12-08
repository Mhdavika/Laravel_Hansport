@extends('layouts.backend')
@section('title', 'Daftar User')

@section('content')
<h1 class="h3 mb-4 text-gray-800">Daftar Pengguna</h1>

<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>Nama</th>
            <th>Email</th>
            <th>No. Telepon</th>
            <th>Alamat</th>
            <th>Terdaftar Sejak</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($users as $user)
        <tr>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td>{{ $user->phone ?? '-' }}</td>
            <td>{{ $user->address ?? '-' }}</td>
            <td>{{ $user->created_at->format('d/m/Y') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
