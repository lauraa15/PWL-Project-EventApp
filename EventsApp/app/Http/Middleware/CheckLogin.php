<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckLogin
{
    public function handle(Request $request, Closure $next)
    {
        if (!session()->has('jwt_token')) {
            return redirect('/login');
        }
        return $next($request);
    }
}
