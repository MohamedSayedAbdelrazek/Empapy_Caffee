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

// Checkout
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/checkout/success/{order}', [CheckoutController::class, 'success'])->name('checkout.success');

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
Route::post('/coupon/validate', function (Illuminate\Http\Request $request) {
    $request->validate([
        'code' => 'required|string',
        'order_total' => 'required|numeric|min:0'
    ]);

    $coupon = App\Models\Coupon::where('code', strtoupper($request->code))->first();

    if (!$coupon) {
        return response()->json(['valid' => false, 'message' => 'كود الخصم غير صحيح']);
    }

    if (!$coupon->isValid()) {
        return response()->json(['valid' => false, 'message' => 'كود الخصم منتهي أو غير نشط']);
    }

    if ($coupon->min_order_amount && $request->order_total < $coupon->min_order_amount) {
        return response()->json([
            'valid' => false,
            'message' => 'الحد الأدنى للطلب ' . number_format($coupon->min_order_amount) . ' ج.م'
        ]);
    }

    $discount = $coupon->calculateDiscount($request->order_total);

    return response()->json([
        'valid' => true,
        'discount' => $discount,
        'message' => 'تم تطبيق الخصم: ' . number_format($discount) . ' ج.م'
    ]);
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Products
    Route::resource('products', ProductController::class);
    Route::get('products-trashed', [ProductController::class, 'trashed'])->name('products.trashed');
    Route::post('products/{id}/restore', [ProductController::class, 'restore'])->name('products.restore');
    Route::delete('products/{id}/force-delete', [ProductController::class, 'forceDelete'])->name('products.force-delete');

    // Categories
    Route::resource('categories', CategoryController::class);

    // Orders
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.status');
    Route::patch('/orders/{order}/payment-status', [OrderController::class, 'updatePaymentStatus'])->name('orders.payment-status');
    Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');

    // Users
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');

    // Coupons
    Route::resource('coupons', CouponController::class);

    // Notifications
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\NotificationController::class, 'index'])->name('index');
        Route::get('/get', [\App\Http\Controllers\Admin\NotificationController::class, 'getNotifications'])->name('get');
        Route::get('/count', [\App\Http\Controllers\Admin\NotificationController::class, 'getUnreadCount'])->name('count');
        Route::post('/{notification}/read', [\App\Http\Controllers\Admin\NotificationController::class, 'markAsRead'])->name('read');
        Route::post('/mark-all-read', [\App\Http\Controllers\Admin\NotificationController::class, 'markAllAsRead'])->name('mark-all-read');
        Route::delete('/{notification}', [\App\Http\Controllers\Admin\NotificationController::class, 'destroy'])->name('destroy');
        Route::post('/clear-all', [\App\Http\Controllers\Admin\NotificationController::class, 'clearAll'])->name('clear-all');
    });
});

/*
|--------------------------------------------------------------------------
| Auth Routes (Using AuthController)
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:5,1');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');
