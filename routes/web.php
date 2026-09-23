<?php

use App\Http\Controllers\Admin\AnnouncementController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\MarketController as AdminMarketController;
use App\Http\Controllers\Admin\ModerationController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Customer\CartController;
use App\Http\Controllers\Customer\DashboardController as CustomerDashboard;
use App\Http\Controllers\Customer\FavoriteController;
use App\Http\Controllers\Customer\MarketController as CustomerMarketController;
use App\Http\Controllers\Customer\OrderController as CustomerOrderController;
use App\Http\Controllers\Customer\ProductController as CustomerProductController;
use App\Http\Controllers\Customer\ReviewController as CustomerReviewController;
use App\Http\Controllers\Farmer\DashboardController as FarmerDashboard;
use App\Http\Controllers\Farmer\OrderController as FarmerOrderController;
use App\Http\Controllers\Farmer\ProductController as FarmerProductController;
use App\Http\Controllers\Farmer\ProfileController as FarmerProfileController;
use App\Http\Controllers\Farmer\ReviewController as FarmerReviewController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

// ── Public ────────────────────────────────────────────────────────────────────
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::get('/products', [HomeController::class, 'products'])->name('products.index');
Route::get('/products/{product}', [HomeController::class, 'productShow'])->name('products.show');
Route::get('/markets', [HomeController::class, 'markets'])->name('markets.index');
Route::get('/markets/{market}', [HomeController::class, 'marketShow'])->name('markets.show');

// ── Auth ──────────────────────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ── Customer ──────────────────────────────────────────────────────────────────
Route::middleware(['auth', 'role:customer'])->prefix('customer')->name('customer.')->group(function () {
    Route::get('/dashboard', [CustomerDashboard::class, 'index'])->name('dashboard');
    Route::delete('/profile', [CustomerDashboard::class, 'destroyProfile'])->name('profile.destroy');

    // Markets
    Route::get('/markets', [CustomerMarketController::class, 'index'])->name('markets.index');
    Route::get('/markets/{market}', [CustomerMarketController::class, 'show'])->name('markets.show');

    // Products
    Route::get('/products', [CustomerProductController::class, 'index'])->name('products.index');
    Route::get('/products/{product}', [CustomerProductController::class, 'show'])->name('products.show');

    // Cart
    Route::get('/cart', [CartController::class, 'index'])->name('cart');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
    Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

    // Orders
    Route::get('/orders', [CustomerOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/checkout', [CustomerOrderController::class, 'checkout'])->name('orders.checkout');
    Route::post('/orders', [CustomerOrderController::class, 'store'])->name('orders.store');
    Route::get('/orders/{order}', [CustomerOrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{order}/cancel', [CustomerOrderController::class, 'cancel'])->name('orders.cancel');

    // Favorites
    Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index');
    Route::post('/favorites/toggle', [FavoriteController::class, 'toggle'])->name('favorites.toggle');

    // Reviews
    Route::get('/orders/{order}/review', [CustomerReviewController::class, 'create'])->name('reviews.create');
    Route::post('/orders/{order}/review', [CustomerReviewController::class, 'store'])->name('reviews.store');
});

// ── Farmer ────────────────────────────────────────────────────────────────────
Route::middleware(['auth', 'role:farmer'])->prefix('farmer')->name('farmer.')->group(function () {
    Route::get('/dashboard', [FarmerDashboard::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile', [FarmerProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [FarmerProfileController::class, 'update'])->name('profile.update');

    // Products
    Route::get('/products', [FarmerProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [FarmerProductController::class, 'create'])->name('products.create');
    Route::post('/products', [FarmerProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}/edit', [FarmerProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [FarmerProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [FarmerProductController::class, 'destroy'])->name('products.destroy');
    Route::post('/products/{product}/toggle', [FarmerProductController::class, 'toggleAvailability'])->name('products.toggle');

    // Orders
    Route::get('/orders', [FarmerOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [FarmerOrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{order}/accept', [FarmerOrderController::class, 'accept'])->name('orders.accept');
    Route::post('/orders/{order}/decline', [FarmerOrderController::class, 'decline'])->name('orders.decline');
    Route::post('/orders/{order}/ready', [FarmerOrderController::class, 'markReady'])->name('orders.ready');
    Route::post('/orders/{order}/complete', [FarmerOrderController::class, 'complete'])->name('orders.complete');

    // Reviews
    Route::get('/reviews', [FarmerReviewController::class, 'index'])->name('reviews.index');
    Route::post('/reviews/{review}/reply', [FarmerReviewController::class, 'reply'])->name('reviews.reply');
});

// ── Admin ─────────────────────────────────────────────────────────────────────
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');

    // Users
    Route::get('/users/farmers', [AdminUserController::class, 'farmers'])->name('users.farmers');
    Route::get('/users/customers', [AdminUserController::class, 'customers'])->name('users.customers');
    Route::post('/users/{user}/approve-farmer', [AdminUserController::class, 'approveFarmer'])->name('users.approve-farmer');
    Route::post('/users/{user}/suspend-farmer', [AdminUserController::class, 'suspendFarmer'])->name('users.suspend-farmer');
    Route::post('/users/{user}/toggle-customer', [AdminUserController::class, 'toggleCustomer'])->name('users.toggle-customer');

    // Markets
    Route::get('/markets', [AdminMarketController::class, 'index'])->name('markets.index');
    Route::get('/markets/create', [AdminMarketController::class, 'create'])->name('markets.create');
    Route::post('/markets', [AdminMarketController::class, 'store'])->name('markets.store');
    Route::get('/markets/{market}/edit', [AdminMarketController::class, 'edit'])->name('markets.edit');
    Route::put('/markets/{market}', [AdminMarketController::class, 'update'])->name('markets.update');
    Route::delete('/markets/{market}', [AdminMarketController::class, 'destroy'])->name('markets.destroy');

    // Categories
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
    Route::post('/categories/{category}/toggle', [CategoryController::class, 'toggle'])->name('categories.toggle');

    // Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');

    // Moderation
    Route::get('/moderation', [ModerationController::class, 'index'])->name('moderation.index');
    Route::post('/moderation/reviews/{review}/hide', [ModerationController::class, 'hideReview'])->name('moderation.reviews.hide');
    Route::post('/moderation/reviews/{review}/restore', [ModerationController::class, 'restoreReview'])->name('moderation.reviews.restore');
    Route::post('/moderation/products/{product}/remove', [ModerationController::class, 'removeProduct'])->name('moderation.products.remove');

    // Announcements
    Route::get('/announcements', [AnnouncementController::class, 'index'])->name('announcements.index');
    Route::post('/announcements', [AnnouncementController::class, 'store'])->name('announcements.store');
    Route::post('/announcements/{announcement}/publish', [AnnouncementController::class, 'publish'])->name('announcements.publish');
    Route::delete('/announcements/{announcement}', [AnnouncementController::class, 'destroy'])->name('announcements.destroy');
});
