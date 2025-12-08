<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'HanSport')</title>
    <link rel="stylesheet" href="{{ asset('frontend/styles/chat_style.css') }}"> <!-- Memuat chat_style.css -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @yield('styles') <!-- Jika ada CSS tambahan untuk halaman ini -->
</head>

<body class="{{ request()->is('chat*') ? 'chat-page' : '' }}"> <!-- Menambahkan kelas 'chat-page' hanya di halaman chat -->

    <!-- Navbar hanya akan muncul jika bukan di halaman chat -->
    @unless(request()->is('chat*'))
        <ul class="navbar_user">
            <!-- Ikon Pesan Admin -->
            <li>
                <a href="{{ route('admin.chat.index') }}" class="btn btn-info">
                    <i class="fa fa-comment"></i>
                </a>
            </li>
        </ul>
    @endunless

    <div class="container">
        @yield('content') <!-- Konten halaman spesifik -->
    </div>

</body>
</html>
