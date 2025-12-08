<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\RegionController;
use App\Http\Controllers\InfoController;
use App\Http\Controllers\ChatController;

// Admin Controllers
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\ContactController as AdminContactController;
use App\Http\Controllers\Admin\SearchController as AdminSearchController;

/*
|---------------------------------------------------------------------- 
| ROUTE AJAX WILAYAH (LARAVOLT/INDONESIA)
|---------------------------------------------------------------------- 
*/
Route::get('/ajax/provinces', [RegionController::class, 'getProvinces']);
Route::get('/ajax/cities', [RegionController::class, 'getCities']);
Route::get('/ajax/districts', [RegionController::class, 'getDistricts']);

/**
 * 🔐 AUTHENTICATION
 */
Route::get('/', function () {
    return redirect()->route('shop.index');
});

// LOGIN / REGISTER
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

/**
 * 🌐 HALAMAN PUBLIK
 */
Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
Route::get('/product/{id}', [ProductController::class, 'show'])->name('product.show');
Route::get('/info-promo', [InfoController::class, 'index'])->name('info-promo.index');
Route::get('/info-promo/{id}', [InfoController::class, 'show'])->name('info-promo.show');
Route::get('/contact', function () {
    return view('frontend.contact.index');
})->name('contact.index');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
Route::get('/search', [SearchController::class, 'index'])->name('search');

/**
 * 🛒 CART - TAMBAH KE KERANJANG
 */
Route::post('/cart', [CartController::class, 'store'])->name('cart.store');

/**
 * 👤 HALAMAN USER (BUTUH LOGIN)
 */
Route::middleware(['auth'])->group(function () {
    Route::get('/homepage', [HomeController::class, 'index'])->name('homepage');
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::put('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{id}', [CartController::class, 'destroy'])->name('cart.remove');
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/orders', [ProfileController::class, 'orders'])->name('profile.orders');
    Route::get('/profile/orders/{id}', [ProfileController::class, 'orderDetail'])->name('profile.order.detail');
    Route::get('/profile/likes', [LikeController::class, 'likedProducts'])->name('profile.likes');
    Route::post('/profile/upload-photo', [ProfileController::class, 'uploadPhoto'])->name('profile.upload.photo');
    Route::get('/profile/password', [ProfileController::class, 'editPassword'])->name('profile.password');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
    Route::post('/like/{productId}', [LikeController::class, 'toggle'])->name('like.toggle');
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'submit'])->name('checkout.submit');
    Route::get('/checkout/confirm', [CheckoutController::class, 'confirm'])->name('checkout.confirm');
    Route::post('/checkout/finalize', [CheckoutController::class, 'finalize'])->name('checkout.finalize');
    Route::post('/checkout/upload-proof', [CheckoutController::class, 'uploadProof'])->name('checkout.upload-proof');
    Route::get('/checkout/success', function () {
        return view('frontend.checkout.success');
    })->name('checkout.success');
    
    // Chat Routes for Users
    Route::prefix('chat')->name('chat.')->group(function () {
        Route::get('/', [ChatController::class, 'index'])->name('index');
        Route::post('/', [ChatController::class, 'store'])->name('store');
    });
});

// Admin Routes
Route::prefix('admin')->middleware('auth')->name('admin.')->group(function () {
    // Dashboard Admin
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Produk Admin
    Route::resource('products', AdminProductController::class)->except(['show']);
    
    // Pesanan Admin
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');
    Route::put('/orders/{id}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
    
    // Daftar Pengguna Admin
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    
    // Info & Promo Admin
    Route::resource('blogs', BlogController::class)->except(['show']);
    
    // Kontak Admin
    Route::get('/contacts', [AdminContactController::class, 'index'])->name('contacts.index');
    
    // Pencarian Admin
    Route::get('/search', [AdminSearchController::class, 'index'])->name('search.index');
    
    // Chat Routes for Admin
    Route::prefix('chat')->name('chat.')->group(function () {
        Route::get('/users', [ChatController::class, 'adminChats'])->name('users');
        Route::get('/chat/{userId}', [ChatController::class, 'adminChatIndex'])->name('index');
        Route::post('/store', [ChatController::class, 'adminStore'])->name('store');
    });
});