<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

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
        // Register default authentication gates for roles
        Gate::define('admin', function ($user) {
            return $user['role_id'] === 1;
        });

        Gate::define('finance', function ($user) {
            return $user['role_id'] === 2;
        });

        Gate::define('organizer', function ($user) {
            return $user['role_id'] === 3;
        });

        Gate::define('member', function ($user) {
            return $user['role_id'] === 4;
        });
    }
}
