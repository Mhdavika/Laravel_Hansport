@extends('layouts.frontend')

@section('title', 'Profile')

@push('styles')
<link rel="stylesheet" href="{{ asset('frontend/styles/profile_styles.css') }}">
<link href="{{ asset('frontend/plugins/font-awesome-4.7.0/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css">
@endpush

@section('content')
<div class="profile-page">
    @include('layouts.breadcrumbs')

    <div class="container mt-5">
        <div class="profile-wrapper d-flex flex-nowrap">

            <div class="profile-sidebar">
                @include('layouts.sidebar')
            </div>

            <div class="profile-main">
                @if(session('success'))
                <div class="alert alert-success" id="success-alert">{{ session('success') }}</div>
                @endif

                <div class="card profile-card p-4 shadow-sm rounded">
                    <div class="profile-info mb-4">
                        <div class="row mb-2">
                            <div class="col-md-4 fw-semibold"><i class="fa fa-user mr-2"></i> Nama</div>
                            <div class="col-md-8">: {{ $user->name }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-4 fw-semibold"><i class="fa fa-envelope mr-2"></i> Email</div>
                            <div class="col-md-8">: {{ $user->email }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-4 fw-semibold"><i class="fa fa-phone mr-2"></i> Nomor Telepon</div>
                            <div class="col-md-8">: {{ $user->phone }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-4 fw-semibold"><i class="fa fa-home mr-2"></i> Alamat</div>
                            <div class="col-md-8">: {{ $user->address }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-4 fw-semibold"><i class="fa fa-map mr-2"></i> Provinsi</div>
                            <div class="col-md-8">: {{ $user->province }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-4 fw-semibold"><i class="fa fa-map-marker mr-2"></i> Kota</div>
                            <div class="col-md-8">: {{ $user->city }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-4 fw-semibold"><i class="fa fa-location-arrow mr-2"></i> Kecamatan</div>
                            <div class="col-md-8">: {{ $user->district }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4 fw-semibold"><i class="fa fa-envelope-o mr-2"></i> Kode Pos</div>
                            <div class="col-md-8">: {{ $user->postal_code }}</div>
                        </div>

                    </div>

                    <a href="{{ route('profile.edit') }}" class="btn btn-dark w-100">Edit Profil</a>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('frontend/js/profile_custom.js') }}"></script>
@endpush