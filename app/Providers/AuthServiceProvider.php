<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Gate::define('admin', function ($user) {
            return $user->is_admin;
        });

        Gate::define('add-to-cart', function ($user) {
            return Auth::check() && !$user->is_admin;
        });

        Gate::define('pay-order', function ($user) {
            return Auth::check() && !$user->is_admin;
        });
    }
}
