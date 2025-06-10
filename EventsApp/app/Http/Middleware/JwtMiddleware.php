<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;

class JwtMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        try {
            $token = session('jwt_token');
            
            if (!$token) {
                return redirect()->route('login');
            }

            // Decode token
            $decoded = JWT::decode($token, new Key('SECRET_KEY', 'HS256'));
            
            // Store user data in session if not already stored
            if (!session()->has('user')) {
                session(['user' => [
                    'id' => $decoded->id,
                    'name' => $decoded->name,
                    'email' => $decoded->email,
                    'role_id' => $decoded->role_id
                ]]);
            }

            // Check roles if specified
            if (!empty($roles)) {
                $roleId = session('user')['role_id'];
                $allowedRoles = [
                    'admin' => 1,
                    'finance' => 2,
                    'organizer' => 3,
                    'member' => 4
                ];

                $currentRoute = $request->route()->getName();
                $hasRole = false;

                foreach ($roles as $role) {
                    if ($roleId === $allowedRoles[$role]) {
                        $hasRole = true;
                        break;
                    }
                }

                if (!$hasRole) {
                    if ($request->expectsJson()) {
                        return response()->json(['error' => 'Unauthorized access'], 403);
                    }
                    return redirect()->route('login')->with('error', 'Unauthorized access. Please login with appropriate credentials.');
                }
            }

            return $next($request);
        } catch (Exception $e) {
            session()->forget(['jwt_token', 'user']);
            return redirect()->route('login');
        }
    }
}
