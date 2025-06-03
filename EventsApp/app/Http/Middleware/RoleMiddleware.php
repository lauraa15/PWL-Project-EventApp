<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $roleId): Response
    {
        // Ambil data JWT dari header Authorization
        $token = $request->bearerToken();
        if (!$token) {
            return redirect('/login')->with('error', 'Harap login terlebih dahulu.');
        }

        try {
            $payload = json_decode(base64_decode(explode('.', $token)[1]), true);
            if ($payload['role_id'] != $roleId) {
                abort(403, 'Akses ditolak: Anda tidak punya izin.');
            }

            return $next($request);

        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'Token tidak valid.');
        }
    }
}
