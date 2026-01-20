<?php

namespace App\Providers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use App\Observers\OrderObserver;
use App\Observers\ReviewObserver;
use App\Observers\UserObserver;
use App\Policies\ProductPolicy;
use App\Services\CartService;
use App\Services\ProductService;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register Services as singletons
        $this->app->singleton(ProductService::class, function ($app) {
            return new ProductService();
        });

        $this->app->singleton(CartService::class, function ($app) {
            return new CartService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register Policies
        Gate::policy(Product::class, ProductPolicy::class);

        // Register Observers for notifications
        Order::observe(OrderObserver::class);
        User::observe(UserObserver::class);
        Review::observe(ReviewObserver::class);

        // Only force HTTPS when explicitly enabled via .env
        // Set FORCE_HTTPS=true in .env when you have SSL certificate
        if (config('app.force_https', false)) {
            URL::forceScheme('https');
        }
    }
}
