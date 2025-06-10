<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('jwt', function ($app) {
            return new JWT();
        });

        $this->app->singleton('jwt.key', function ($app) {
            return new Key(env('JWT_SECRET', 'SECRET_KEY'), 'HS256');
        });
    }

    public function boot()
    {
        //
    }
}
