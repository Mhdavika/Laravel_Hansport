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