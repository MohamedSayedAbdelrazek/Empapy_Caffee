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
Route::post('/checkout/calculate-shipping', [CheckoutController::class, 'calculateShipping'])->name('checkout.calculate-shipping'); // Added route
Route::get('/checkout/success/{order}', [CheckoutController::class, 'success'])->name('checkout.success');

// Payment Routes (Stripe) - DISABLED: Will be replaced with Paymob
// Route::post('/payment/create-intent', [App\Http\Controllers\PaymentController::class, 'createIntent'])
//     ->name('payment.create-intent');
// Route::post('/stripe/webhook', [App\Http\Controllers\PaymentController::class, 'webhook'])
//     ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class])
//     ->name('stripe.webhook');

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

// DISABLED: Reviews Routes - uncomment when ready
// Route::prefix('reviews')->name('reviews.')->group(function () {
//     Route::post('/', [ReviewController::class, 'store'])->name('store')->middleware('auth');
//     Route::delete('/{review}', [ReviewController::class, 'destroy'])->name('destroy')->middleware('auth');
// });

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
| Admin Routes - Permission Based Access
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->middleware(['staff'])->group(function () {
    // Dashboard (requires view-analytics)
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard')->middleware('permission:view-analytics');

    // Products (requires product permissions)
    Route::get('products', [ProductController::class, 'index'])->name('products.index')->middleware('permission:view-products');
    Route::get('products/create', [ProductController::class, 'create'])->name('products.create')->middleware('permission:create-products');
    Route::post('products', [ProductController::class, 'store'])->name('products.store')->middleware('permission:create-products');
    Route::get('products/{product}', [ProductController::class, 'show'])->name('products.show')->middleware('permission:view-products');
    Route::get('products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit')->middleware('permission:edit-products');
    Route::put('products/{product}', [ProductController::class, 'update'])->name('products.update')->middleware('permission:edit-products');
    Route::delete('products/{product}', [ProductController::class, 'destroy'])->name('products.destroy')->middleware('permission:delete-products');
    Route::get('products-trashed', [ProductController::class, 'trashed'])->name('products.trashed')->middleware('permission:delete-products');
    Route::post('products/{id}/restore', [ProductController::class, 'restore'])->name('products.restore')->middleware('permission:delete-products');
    Route::delete('products/{id}/force-delete', [ProductController::class, 'forceDelete'])->name('products.force-delete')->middleware('permission:delete-products');

    // Categories (requires category permissions)
    Route::get('categories', [CategoryController::class, 'index'])->name('categories.index')->middleware('permission:view-categories');
    Route::get('categories/create', [CategoryController::class, 'create'])->name('categories.create')->middleware('permission:create-categories');
    Route::post('categories', [CategoryController::class, 'store'])->name('categories.store')->middleware('permission:create-categories');
    Route::get('categories/{category}', [CategoryController::class, 'show'])->name('categories.show')->middleware('permission:view-categories');
    Route::get('categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit')->middleware('permission:edit-categories');
    Route::put('categories/{category}', [CategoryController::class, 'update'])->name('categories.update')->middleware('permission:edit-categories');
    Route::delete('categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy')->middleware('permission:delete-categories');

    // Users/Customers (requires view-users)
    Route::get('/users', [UserController::class, 'index'])->name('users.index')->middleware('permission:view-users');
    Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show')->middleware('permission:view-users');

    // Coupons (requires coupon permissions)
    Route::get('coupons', [CouponController::class, 'index'])->name('coupons.index')->middleware('permission:view-coupons');
    Route::get('coupons/create', [CouponController::class, 'create'])->name('coupons.create')->middleware('permission:create-coupons');
    Route::post('coupons', [CouponController::class, 'store'])->name('coupons.store')->middleware('permission:create-coupons');
    Route::get('coupons/{coupon}', [CouponController::class, 'show'])->name('coupons.show')->middleware('permission:view-coupons');
    Route::get('coupons/{coupon}/edit', [CouponController::class, 'edit'])->name('coupons.edit')->middleware('permission:edit-coupons');
    Route::put('coupons/{coupon}', [CouponController::class, 'update'])->name('coupons.update')->middleware('permission:edit-coupons');
    Route::delete('coupons/{coupon}', [CouponController::class, 'destroy'])->name('coupons.destroy')->middleware('permission:delete-coupons');

    // Announcements (requires announcement permissions)
    Route::get('announcements', [App\Http\Controllers\Admin\AnnouncementController::class, 'index'])->name('announcements.index')->middleware('permission:view-announcements');
    Route::get('announcements/create', [App\Http\Controllers\Admin\AnnouncementController::class, 'create'])->name('announcements.create')->middleware('permission:manage-announcements');
    Route::post('announcements', [App\Http\Controllers\Admin\AnnouncementController::class, 'store'])->name('announcements.store')->middleware('permission:manage-announcements');
    Route::get('announcements/{announcement}', [App\Http\Controllers\Admin\AnnouncementController::class, 'show'])->name('announcements.show')->middleware('permission:view-announcements');
    Route::get('announcements/{announcement}/edit', [App\Http\Controllers\Admin\AnnouncementController::class, 'edit'])->name('announcements.edit')->middleware('permission:manage-announcements');
    Route::put('announcements/{announcement}', [App\Http\Controllers\Admin\AnnouncementController::class, 'update'])->name('announcements.update')->middleware('permission:manage-announcements');
    Route::delete('announcements/{announcement}', [App\Http\Controllers\Admin\AnnouncementController::class, 'destroy'])->name('announcements.destroy')->middleware('permission:manage-announcements');
    Route::post('announcements/reorder', [App\Http\Controllers\Admin\AnnouncementController::class, 'reorder'])->name('announcements.reorder')->middleware('permission:manage-announcements');
    Route::post('announcements/{announcement}/toggle', [App\Http\Controllers\Admin\AnnouncementController::class, 'toggleActive'])->name('announcements.toggle')->middleware('permission:manage-announcements');

    // Settings (requires edit-settings)
    Route::get('settings', [App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('settings.index')->middleware('permission:edit-settings');
    Route::put('settings', [App\Http\Controllers\Admin\SettingsController::class, 'update'])->name('settings.update')->middleware('permission:edit-settings');

    // Shipping Zones (requires manage-site)
    Route::get('shipping-zones', [App\Http\Controllers\Admin\ShippingZoneController::class, 'index'])->name('shipping-zones.index')->middleware('permission:manage-site');
    Route::put('shipping-zones/{shipping_zone}', [App\Http\Controllers\Admin\ShippingZoneController::class, 'update'])->name('shipping-zones.update')->middleware('permission:manage-site');

    // Staff Management (requires user management permissions)
    Route::get('staff', [\App\Http\Controllers\Admin\StaffController::class, 'index'])->name('staff.index')->middleware('permission:view-users');
    Route::get('staff/create', [\App\Http\Controllers\Admin\StaffController::class, 'create'])->name('staff.create')->middleware('permission:create-users');
    Route::post('staff', [\App\Http\Controllers\Admin\StaffController::class, 'store'])->name('staff.store')->middleware('permission:create-users');
    Route::get('staff/{staff}', [\App\Http\Controllers\Admin\StaffController::class, 'show'])->name('staff.show')->middleware('permission:view-users');
    Route::get('staff/{staff}/edit', [\App\Http\Controllers\Admin\StaffController::class, 'edit'])->name('staff.edit')->middleware('permission:edit-users');
    Route::put('staff/{staff}', [\App\Http\Controllers\Admin\StaffController::class, 'update'])->name('staff.update')->middleware('permission:edit-users');
    Route::delete('staff/{staff}', [\App\Http\Controllers\Admin\StaffController::class, 'destroy'])->name('staff.destroy')->middleware('permission:delete-users');

    // Contact Messages (requires contact permissions)
    Route::get('contacts', [\App\Http\Controllers\Admin\ContactController::class, 'index'])->name('contacts.index')->middleware('permission:view-contacts');
    Route::get('contacts/{contact}', [\App\Http\Controllers\Admin\ContactController::class, 'show'])->name('contacts.show')->middleware('permission:view-contacts');
    Route::put('contacts/{contact}', [\App\Http\Controllers\Admin\ContactController::class, 'update'])->name('contacts.update')->middleware('permission:manage-contacts');
    Route::delete('contacts/{contact}', [\App\Http\Controllers\Admin\ContactController::class, 'destroy'])->name('contacts.destroy')->middleware('permission:manage-contacts');
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

Route::prefix('admin/loyalty')->name('admin.loyalty.')->middleware(['auth', 'staff'])->group(function () {
    // Dashboard (view-loyalty)
    Route::get('/', [App\Http\Controllers\Admin\LoyaltyController::class, 'index'])->name('dashboard')->middleware('permission:view-loyalty');

    // Point Rules (manage-loyalty)
    Route::get('/rules', [App\Http\Controllers\Admin\LoyaltyController::class, 'rules'])->name('rules')->middleware('permission:view-loyalty');
    Route::get('/rules/create', [App\Http\Controllers\Admin\LoyaltyController::class, 'createRule'])->name('rules.create')->middleware('permission:manage-loyalty');
    Route::post('/rules', [App\Http\Controllers\Admin\LoyaltyController::class, 'storeRule'])->name('rules.store')->middleware('permission:manage-loyalty');
    Route::get('/rules/{rule}/edit', [App\Http\Controllers\Admin\LoyaltyController::class, 'editRule'])->name('rules.edit')->middleware('permission:manage-loyalty');
    Route::put('/rules/{rule}', [App\Http\Controllers\Admin\LoyaltyController::class, 'updateRule'])->name('rules.update')->middleware('permission:manage-loyalty');
    Route::delete('/rules/{rule}', [App\Http\Controllers\Admin\LoyaltyController::class, 'destroyRule'])->name('rules.destroy')->middleware('permission:manage-loyalty');

    // Rewards (manage-loyalty)
    Route::get('/rewards', [App\Http\Controllers\Admin\LoyaltyController::class, 'rewards'])->name('rewards')->middleware('permission:view-loyalty');
    Route::get('/rewards/create', [App\Http\Controllers\Admin\LoyaltyController::class, 'createReward'])->name('rewards.create')->middleware('permission:manage-loyalty');
    Route::post('/rewards', [App\Http\Controllers\Admin\LoyaltyController::class, 'storeReward'])->name('rewards.store')->middleware('permission:manage-loyalty');
    Route::get('/rewards/{reward}/edit', [App\Http\Controllers\Admin\LoyaltyController::class, 'editReward'])->name('rewards.edit')->middleware('permission:manage-loyalty');
    Route::put('/rewards/{reward}', [App\Http\Controllers\Admin\LoyaltyController::class, 'updateReward'])->name('rewards.update')->middleware('permission:manage-loyalty');
    Route::delete('/rewards/{reward}', [App\Http\Controllers\Admin\LoyaltyController::class, 'destroyReward'])->name('rewards.destroy')->middleware('permission:manage-loyalty');

    // User Points Management (manage-loyalty)
    Route::get('/users', [App\Http\Controllers\Admin\LoyaltyController::class, 'users'])->name('users')->middleware('permission:view-loyalty');
    Route::get('/users/{user}', [App\Http\Controllers\Admin\LoyaltyController::class, 'showUser'])->name('users.show')->middleware('permission:view-loyalty');
    Route::post('/users/{user}/adjust', [App\Http\Controllers\Admin\LoyaltyController::class, 'adjustPoints'])->name('users.adjust')->middleware('permission:manage-loyalty');

    // Transactions (view-loyalty)
    Route::get('/transactions', [App\Http\Controllers\Admin\LoyaltyController::class, 'transactions'])->name('transactions')->middleware('permission:view-loyalty');
});
