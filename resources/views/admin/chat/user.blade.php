{{-- filepath: resources/views/admin/chat/user.blade.php --}}
@extends('layouts.backend')

@section('title', 'Daftar Chat')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Daftar Chat dengan User</h6>
                </div>
                <div class="card-body">
                    @if($users->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>No</th>
                                        <th>Nama User</th>
                                        <th>Email</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($users as $key => $user)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>
                                                <a href="/admin/chat/chat/{{ $user->id }}" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-comments"></i> Chat
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info" role="alert">
                            <i class="fas fa-info-circle"></i> Belum ada user yang chat dengan admin.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection