<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureTokenExists
{
    public function handle(Request $request, Closure $next)
    {
        if (!isset($_COOKIE['token']) && !session()->has('token')) {
            return redirect('/login');
        }

        return $next($request);
    }
}

