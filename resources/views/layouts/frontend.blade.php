<!DOCTYPE html>
<html lang="en">

<head>
    <title>@yield('title', 'HanSport')</title>
    <meta charset="utf-8">
    <meta name="description" content="HanSport">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon_io/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon_io/favicon-16x16.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('favicon_io/apple-touch-icon.png') }}">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('frontend/styles/bootstrap4/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/plugins/font-awesome-4.7.0/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/plugins/OwlCarousel2-2.2.1/owl.carousel.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/plugins/OwlCarousel2-2.2.1/owl.theme.default.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/plugins/OwlCarousel2-2.2.1/animate.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/styles/main_styles.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/styles/responsive.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/styles/blogs.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/styles/message_style.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/styles/contact_styles.css') }}">

    @stack('styles')

    <style>
        html { scroll-behavior: smooth; }
        .favorite::before, .favorite::after { content:none!important; }

        /* ===============================
           HIDE/SHOW HEADER ON SCROLL
           Scroll down -> hide header
           Scroll up   -> show header
        =============================== */
        header.header {
            transition: transform 1s ease, top 2s ease;
            will-change: transform;
        }
        header.header.is-hidden {
            transform: translateY(-100%);
        }
    </style>
</head>

<body class="{{ request()->is('chat*') ? 'chat-page' : '' }}">

<div class="super_container">

    <!-- HEADER -->
    <header class="header trans_300">

        <!-- TOP NAV -->
        <div class="top_nav">
            <div class="container">
                <div class="row">

                    <div class="col-md-6">
                        <div class="top_nav_left">Gratis Ongkir untuk Pengguna Baru</div>
                    </div>

                    <div class="col-md-6 text-right">
                        <div class="top_nav_right">
                            <ul class="top_nav_menu">

                                <li class="account">
                                    <a href="#">
                                        {{ Auth::user()->name ?? 'My Account' }}
                                        <i class="fa fa-angle-down"></i>
                                    </a>

                                    <ul class="account_selection">
                                        @auth
                                            <li>
                                                <form method="POST" action="{{ route('logout') }}">
                                                    @csrf
                                                    <button type="submit" style="background:none;border:none;cursor:pointer;">
                                                        <i class="fa fa-sign-in"></i> Keluar
                                                    </button>
                                                </form>
                                            </li>
                                        @else
                                            <li><a href="{{ route('login') }}"><i class="fa fa-sign-in"></i> Login</a></li>
                                            <li><a href="{{ route('register') }}"><i class="fa fa-user-plus"></i> Register</a></li>
                                        @endauth
                                    </ul>

                                </li>

                            </ul>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- MAIN NAV -->
        <div class="main_nav_container">
            <div class="container">
                <div class="row">

                    <div class="col-lg-12 text-right">

                        <div class="logo_container">
                            <a href="{{ route('homepage') }}">han<span>sport</span></a>
                        </div>

                        <nav class="navbar">

                            <ul class="navbar_menu">
                                <li><a href="{{ route('homepage') }}">home</a></li>
                                <li><a href="{{ route('shop.index') }}">shop</a></li>
                                <li><a href="{{ route('info-promo.index') }}">info & promo</a></li>
                                <li><a href="{{ route('contact.index') }}">contact</a></li>
                            </ul>

                            <ul class="navbar_user">
                                <li>
                                    <a href="#" id="search-icon"><i class="fa fa-search"></i></a>
                                </li>

                                <li id="search-form-wrapper" class="d-none">
                                    <form action="{{ route('search') }}" method="GET">
                                        <input type="text" name="query" placeholder="Cari produk..." style="padding:5px 10px;border-radius:5px;border:1px solid #ccc;margin-left:10px;">
                                    </form>
                                </li>

                                <li><a href="{{ route('profile.index') }}"><i class="fa fa-user"></i></a></li>

                                <li class="checkout" style="position:relative;">
                                    <a href="{{ route('cart.index') }}">
                                        <i class="fa fa-shopping-cart"></i>
                                        @if(isset($cartCount) && $cartCount > 0)
                                            <span class="checkout_items">{{ $cartCount }}</span>
                                        @endif
                                    </a>
                                </li>

                                <li>
                                    <a href="{{ route('chat.index') }}" class="btn btn-dark">
                                        <i class="fa fa-comment"></i>
                                    </a>
                                </li>

                            </ul>

                            <div class="hamburger_container">
                                <i class="fa fa-bars"></i>
                            </div>

                        </nav>

                    </div>

                </div>
            </div>
        </div>

    </header>

    <!-- MENU MOBILE -->
    <div class="fs_menu_overlay"></div>

    <div class="hamburger_menu">
        <div class="hamburger_close"><i class="fa fa-times"></i></div>

        <div class="hamburger_menu_content text-right">
            <ul class="menu_top_nav">

                <li class="menu_item has-children">
                    <a href="#">
                        {{ Auth::user()->name ?? 'My Account' }}
                        <i class="fa fa-angle-down"></i>
                    </a>

                    <ul class="menu_selection">
                        @auth
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" style="background:none;border:none;cursor:pointer;">
                                        <i class="fa fa-sign-in"></i> Keluar
                                    </button>
                                </form>
                            </li>
                        @endauth
                    </ul>
                </li>

                <li class="menu_item"><a href="{{ route('homepage') }}">home</a></li>
                <li class="menu_item"><a href="{{ route('shop.index') }}">shop</a></li>
                <li class="menu_item"><a href="{{ route('info-promo.index') }}">info & promo</a></li>
                <li class="menu_item"><a href="{{ route('contact.index') }}">contact</a></li>

            </ul>
        </div>
    </div>

    <!-- CONTENT -->
    @yield('content')

    <!-- FOOTER -->
    <footer class="footer">
        <div class="container">

            <div class="row">

                
            </div>

            <div class="row mt-3">
                <div class="col-lg-12">
                    <div class="footer_nav_container">
                        <div class="cr">
                            ©2025 All Rights Reserved. Made with <i class="fa fa-heart-o"></i>
                            by <a href="{{ route('homepage') }}">HanSport</a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </footer>

</div>

<!-- JS -->
<script src="{{ asset('frontend/js/jquery-3.2.1.min.js') }}"></script>
<script src="{{ asset('frontend/styles/bootstrap4/popper.js') }}"></script>
<script src="{{ asset('frontend/styles/bootstrap4/bootstrap.min.js') }}"></script>
<script src="{{ asset('frontend/plugins/Isotope/isotope.pkgd.min.js') }}"></script>
<script src="{{ asset('frontend/plugins/OwlCarousel2-2.2.1/owl.carousel.js') }}"></script>
<script src="{{ asset('frontend/plugins/easing/easing.js') }}"></script>
<script src="{{ asset('frontend/js/custom.js') }}"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

@stack('scripts')

<!-- Toggle Search -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const icon = document.getElementById('search-icon');
        const wrapper = document.getElementById('search-form-wrapper');

        if (icon && wrapper) {
            icon.addEventListener('click', function(e) {
                e.preventDefault();
                wrapper.classList.toggle('d-none');
            });
        }
    });
</script>

<!-- Hide/Show Header on Scroll -->
<script>
document.addEventListener("DOMContentLoaded", function () {
    const header = document.querySelector("header.header");
    if (!header) return;

    let lastScrollY = window.scrollY;
    let ticking = false;
    let lastDirection = null; // Menyimpan arah scroll terakhir
    const THRESHOLD = 8; // Batas kecil pergerakan scroll
    const DELAY = 100; // Delay dalam ms (100ms)
    let timeout;
    
    function handleScroll() {
        const currentScrollY = window.scrollY;
        const diff = currentScrollY - lastScrollY;

        // kalau di posisi paling atas, header selalu tampil
        if (currentScrollY <= 0) {
            header.classList.remove("is-hidden");
            lastScrollY = 0;
            return;
        }

        // gerakan kecil diabaikan biar nggak kedip
        if (Math.abs(diff) < THRESHOLD) return;

        // scroll down -> hide, scroll up -> show
        if (diff > 0) header.classList.add("is-hidden");
        else header.classList.remove("is-hidden");

        lastScrollY = currentScrollY;
    }

    window.addEventListener("scroll", function () {
        if (!ticking) {
            window.requestAnimationFrame(function () {
                handleScroll();
                ticking = false;
            });
            ticking = true;
        }
    }, { passive: true });
});
</script>

@if(session('success'))
<script>
Swal.fire({
    toast:true, position:'top-end',
    icon:'success', title:"{{ session('success') }}",
    showConfirmButton:false, timer:3000
});
</script>
@endif

@if(session('error'))
<script>
Swal.fire({
    toast:true, position:'top-end',
    icon:'error', title:"{{ session('error') }}",
    showConfirmButton:false, timer:3000
});
</script>
@endif

</body>
</html>
