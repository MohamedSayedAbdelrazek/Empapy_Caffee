<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

// Home
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::post('/contact', [HomeController::class, 'submitContact'])->name('contact.submit');

// PWA Offline Page
Route::get('/offline', function () {
    return view('offline');
})->name('offline');

// SEO Sitemap
Route::get('/sitemap.xml', [App\Http\Controllers\SitemapController::class, 'index'])->name('sitemap');

// Shop
Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
Route::get('/shop/{product:slug}', [ShopController::class, 'show'])->name('shop.show');

// Cart (AJAX Routes with Rate Limiting)
Route::prefix('cart')->name('cart.')->middleware(['throttle:60,1'])->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::post('/add', [CartController::class, 'add'])->name('add');
    Route::post('/update', [CartController::class, 'update'])->name('update');
    Route::post('/remove', [CartController::class, 'remove'])->name('remove');
    Route::post('/clear', [CartController::class, 'clear'])->name('clear');
    Route::get('/data', [CartController::class, 'getCart'])->name('data');
});

// API Routes for Product Details (used by Quick Shop Modal)
Route::get('/api/products/{id}', [App\Http\Controllers\Api\ProductController::class, 'show'])->name('api.products.show');

// API Routes for FCM Device Registration
Route::prefix('api/device')->middleware('auth')->group(function () {
    Route::post('/register', [App\Http\Controllers\Api\DeviceController::class, 'registerToken'])->name('api.device.register');
    Route::post('/unregister', [App\Http\Controllers\Api\DeviceController::class, 'unregisterToken'])->name('api.device.unregister');
});
Route::get('/api/device/vapid-key', [App\Http\Controllers\Api\DeviceController::class, 'getVapidKey'])->name('api.device.vapid-key');


// Checkout
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/checkout/success/{order}', [CheckoutController::class, 'success'])->name('checkout.success');

// Payment Routes (Stripe)
Route::post('/payment/create-intent', [App\Http\Controllers\PaymentController::class, 'createIntent'])
    ->name('payment.create-intent');
Route::post('/stripe/webhook', [App\Http\Controllers\PaymentController::class, 'webhook'])
    ->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class])
    ->name('stripe.webhook');

// Order Tracking (Public)
Route::get('/track', [App\Http\Controllers\OrderTrackingController::class, 'track'])->name('orders.track');
Route::post('/track', [App\Http\Controllers\OrderTrackingController::class, 'search'])->name('orders.search');

// My Orders (Authenticated)
Route::middleware('auth')->group(function () {
    Route::get('/my-orders', [App\Http\Controllers\OrderTrackingController::class, 'myOrders'])->name('orders.my-orders');
    Route::get('/my-orders/{order}', [App\Http\Controllers\OrderTrackingController::class, 'show'])->name('orders.show');
});

// Wishlist Routes
Route::prefix('wishlist')->name('wishlist.')->group(function () {
    Route::get('/', [WishlistController::class, 'index'])->name('index')->middleware('auth');
    Route::post('/toggle', [WishlistController::class, 'toggle'])->name('toggle');
    Route::get('/count', [WishlistController::class, 'count'])->name('count');
});

// Reviews Routes
Route::prefix('reviews')->name('reviews.')->group(function () {
    Route::post('/', [ReviewController::class, 'store'])->name('store')->middleware('auth');
    Route::delete('/{review}', [ReviewController::class, 'destroy'])->name('destroy')->middleware('auth');
});

// Coupon Validation Route
Route::post('/coupon/validate', [App\Http\Controllers\CouponController::class, 'validate']);

/*
|--------------------------------------------------------------------------
| Admin Routes - Staff Access (Admin + Cashier)
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->middleware(['staff'])->group(function () {
    // Orders (accessible by all staff)
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/kanban', [OrderController::class, 'kanban'])->name('orders.kanban');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.status');
    Route::post('/orders/{order}/status-ajax', [OrderController::class, 'updateStatusAjax'])->name('orders.status-ajax');
    Route::patch('/orders/{order}/payment-status', [OrderController::class, 'updatePaymentStatus'])->name('orders.payment-status');
    Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
    Route::post('/orders/{order}/gift-note', [OrderController::class, 'updateGiftNote'])->name('orders.gift-note');
    Route::get('/orders/{order}/details-ajax', [OrderController::class, 'getOrderDetails'])->name('orders.details-ajax');

    // Notifications (accessible by all staff)
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\NotificationController::class, 'index'])->name('index');
        Route::get('/get', [\App\Http\Controllers\Admin\NotificationController::class, 'getNotifications'])->name('get');
        Route::get('/count', [\App\Http\Controllers\Admin\NotificationController::class, 'getUnreadCount'])->name('count');
        Route::post('/{notification}/read', [\App\Http\Controllers\Admin\NotificationController::class, 'markAsRead'])->name('read');
        Route::post('/mark-all-read', [\App\Http\Controllers\Admin\NotificationController::class, 'markAllAsRead'])->name('mark-all-read');
        Route::delete('/{notification}', [\App\Http\Controllers\Admin\NotificationController::class, 'destroy'])->name('destroy');
        Route::post('/clear-all', [\App\Http\Controllers\Admin\NotificationController::class, 'clearAll'])->name('clear-all');
    });

    // Profile (accessible by all staff)
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\ProfileController::class, 'index'])->name('index');
        Route::put('/', [\App\Http\Controllers\Admin\ProfileController::class, 'update'])->name('update');
        Route::put('/password', [\App\Http\Controllers\Admin\ProfileController::class, 'updatePassword'])->name('password');
        Route::post('/avatar', [\App\Http\Controllers\Admin\ProfileController::class, 'updateAvatar'])->name('avatar');
        Route::delete('/avatar', [\App\Http\Controllers\Admin\ProfileController::class, 'removeAvatar'])->name('avatar.remove');
    });
});

/*
|--------------------------------------------------------------------------
| Admin Routes - Admin Only (Full Access)
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->middleware(['admin'])->group(function () {
    // Dashboard (admin only)
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Products (admin only)
    Route::resource('products', ProductController::class);
    Route::get('products-trashed', [ProductController::class, 'trashed'])->name('products.trashed');
    Route::post('products/{id}/restore', [ProductController::class, 'restore'])->name('products.restore');
    Route::delete('products/{id}/force-delete', [ProductController::class, 'forceDelete'])->name('products.force-delete');

    // Categories (admin only)
    Route::resource('categories', CategoryController::class);

    // Users/Customers (admin only)
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');

    // Coupons (admin only)
    Route::resource('coupons', CouponController::class);

    // Staff Management (admin only)
    Route::resource('staff', \App\Http\Controllers\Admin\StaffController::class);

    // Contact Messages (admin only)
    Route::resource('contacts', \App\Http\Controllers\Admin\ContactController::class)->only(['index', 'show', 'update', 'destroy']);
});

/*
|--------------------------------------------------------------------------
| Auth Routes (Using AuthController)
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:10,1'); // 10 attempts per minute
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:5,1');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

/*
|--------------------------------------------------------------------------
| Account Routes (User Profile)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->prefix('account')->name('account.')->group(function () {
    Route::get('/', [App\Http\Controllers\AccountController::class, 'index'])->name('index');
    Route::get('/profile', [App\Http\Controllers\AccountController::class, 'profile'])->name('profile');
    Route::put('/profile', [App\Http\Controllers\AccountController::class, 'updateProfile'])->name('profile.update');
    Route::put('/password', [App\Http\Controllers\AccountController::class, 'updatePassword'])->name('password');
    Route::post('/avatar', [App\Http\Controllers\AccountController::class, 'updateAvatar'])->name('avatar');
    Route::delete('/avatar', [App\Http\Controllers\AccountController::class, 'removeAvatar'])->name('avatar.remove');
});

/*
|--------------------------------------------------------------------------
| Loyalty Routes (User)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->prefix('loyalty')->name('loyalty.')->group(function () {
    Route::get('/', [App\Http\Controllers\LoyaltyController::class, 'index'])->name('index');
    Route::get('/rewards', [App\Http\Controllers\LoyaltyController::class, 'rewards'])->name('rewards');
    Route::post('/rewards/{reward}/redeem', [App\Http\Controllers\LoyaltyController::class, 'redeem'])->name('redeem');
    Route::get('/redemptions', [App\Http\Controllers\LoyaltyController::class, 'redemptions'])->name('redemptions');
    Route::get('/referral', [App\Http\Controllers\LoyaltyController::class, 'referral'])->name('referral');
    Route::get('/history', [App\Http\Controllers\LoyaltyController::class, 'history'])->name('history');
});

/*
|--------------------------------------------------------------------------
| Admin Loyalty Routes (Added to Admin group)
|--------------------------------------------------------------------------
*/

Route::prefix('admin/loyalty')->name('admin.loyalty.')->middleware(['auth', 'admin'])->group(function () {
    // Dashboard
    Route::get('/', [App\Http\Controllers\Admin\LoyaltyController::class, 'index'])->name('dashboard');

    // Point Rules
    Route::get('/rules', [App\Http\Controllers\Admin\LoyaltyController::class, 'rules'])->name('rules');
    Route::get('/rules/create', [App\Http\Controllers\Admin\LoyaltyController::class, 'createRule'])->name('rules.create');
    Route::post('/rules', [App\Http\Controllers\Admin\LoyaltyController::class, 'storeRule'])->name('rules.store');
    Route::get('/rules/{rule}/edit', [App\Http\Controllers\Admin\LoyaltyController::class, 'editRule'])->name('rules.edit');
    Route::put('/rules/{rule}', [App\Http\Controllers\Admin\LoyaltyController::class, 'updateRule'])->name('rules.update');
    Route::delete('/rules/{rule}', [App\Http\Controllers\Admin\LoyaltyController::class, 'destroyRule'])->name('rules.destroy');

    // Tiers
    Route::get('/tiers', [App\Http\Controllers\Admin\LoyaltyController::class, 'tiers'])->name('tiers');
    Route::get('/tiers/create', [App\Http\Controllers\Admin\LoyaltyController::class, 'createTier'])->name('tiers.create');
    Route::post('/tiers', [App\Http\Controllers\Admin\LoyaltyController::class, 'storeTier'])->name('tiers.store');
    Route::get('/tiers/{tier}/edit', [App\Http\Controllers\Admin\LoyaltyController::class, 'editTier'])->name('tiers.edit');
    Route::put('/tiers/{tier}', [App\Http\Controllers\Admin\LoyaltyController::class, 'updateTier'])->name('tiers.update');
    Route::delete('/tiers/{tier}', [App\Http\Controllers\Admin\LoyaltyController::class, 'destroyTier'])->name('tiers.destroy');

    // Rewards
    Route::get('/rewards', [App\Http\Controllers\Admin\LoyaltyController::class, 'rewards'])->name('rewards');
    Route::get('/rewards/create', [App\Http\Controllers\Admin\LoyaltyController::class, 'createReward'])->name('rewards.create');
    Route::post('/rewards', [App\Http\Controllers\Admin\LoyaltyController::class, 'storeReward'])->name('rewards.store');
    Route::get('/rewards/{reward}/edit', [App\Http\Controllers\Admin\LoyaltyController::class, 'editReward'])->name('rewards.edit');
    Route::put('/rewards/{reward}', [App\Http\Controllers\Admin\LoyaltyController::class, 'updateReward'])->name('rewards.update');
    Route::delete('/rewards/{reward}', [App\Http\Controllers\Admin\LoyaltyController::class, 'destroyReward'])->name('rewards.destroy');

    // User Points Management
    Route::get('/users', [App\Http\Controllers\Admin\LoyaltyController::class, 'users'])->name('users');
    Route::get('/users/{user}', [App\Http\Controllers\Admin\LoyaltyController::class, 'showUser'])->name('users.show');
    Route::post('/users/{user}/adjust', [App\Http\Controllers\Admin\LoyaltyController::class, 'adjustPoints'])->name('users.adjust');

    // Transactions
    Route::get('/transactions', [App\Http\Controllers\Admin\LoyaltyController::class, 'transactions'])->name('transactions');
});
