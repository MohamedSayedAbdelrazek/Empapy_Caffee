<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // Define permission gates dynamically
        // This allows us to use @can('view-products') in Blade
        Gate::before(function ($user, $ability) {
            // Admins have all permissions
            if ($user->role === 'admin') {
                return true;
            }

            // Check if user has the permission
            if ($user->hasPermission($ability)) {
                return true;
            }

            // Default deny
            return null;
        });
    }
}
