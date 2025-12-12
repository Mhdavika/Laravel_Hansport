@php
$routeName = Route::currentRouteName();
$categoryParam = request('category');
$routeMap = [
    'homepage' => 'Home',
    'shop.index' => 'Shop',
    'cart.index' => 'Cart',
    'contact.index' => 'Contact',
    'product.show' => 'Product Detail',
    'search' => 'Search Result',
    'admin.dashboard' => 'Admin Dashboard',
    'profile.index' => 'Profile',
    'profile.edit' => 'Edit Profile',
    'profile.orders' => 'Riwayat Pesanan',
    'profile.likes' => 'Produk Disukai',
];
$pageName = $routeMap[$routeName] ?? 'Page';
@endphp

@if($routeName !== 'homepage')
<div class="breadcrumbs-wrapper">
    <div class="container">
        <div class="breadcrumbs d-flex flex-row align-items-center" style="margin-top: 30px;">
            <ul>
                <!-- Icon Kembali -->
                <li>
                    <a href="javascript:void(0);" class="back-icon" id="back-button">
                        <i class="fa fa-arrow-left"></i> Kembali
                    </a>
                </li>

                <li><a href="{{ route('homepage') }}">Home</a></li>

                @if($routeName === 'shop.index')
                <li><a href="{{ route('shop.index') }}"><i class="fa fa-angle-right" aria-hidden="true"></i> Shop</a></li>

                @if($categoryParam)
                <li class="active">
                    <a href="#"><i class="fa fa-angle-right" aria-hidden="true"></i> {{ $categoryParam }}</a>
                </li>
                @endif
                @else
                <li class="active">
                    <a href="#"><i class="fa fa-angle-right" aria-hidden="true"></i> {{ $pageName }}</a>
                </li>
                @endif
            </ul>
        </div>
    </div>
</div>
@endif

<!-- JavaScript -->
<script>
    // Menambahkan jeda saat klik ikon kembali
    document.getElementById('back-button').addEventListener('click', function() {
        // Menunggu 500ms sebelum kembali
        setTimeout(function() {
            window.history.back();
        }, 500); // Jeda 500ms
    });
</script>

<!-- CSS untuk tata letak ikon dan teks -->
<style>
    /* Styling untuk ikon kembali dan teks */
    .back-icon {
        font-size: 14px;
        color: #000;
        text-decoration: none;
        display: inline-flex;
        align-items: center; /* Memastikan ikon dan teks sejajar */
        margin-right: 10px; /* Jarak antara ikon dan teks */
    }

    .back-icon i {
        margin-right: 5px; /* Memberikan sedikit ruang antara ikon dan teks */
    }
</style>
