<?php

use GuzzleHttp\Client;

return function ($requiredRole) {
    return function ($request, $next) use ($requiredRole) {
        $token = session('jwt_token');
        if (!$token) {
            return redirect('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        try {
            $client = new Client();
            $response = $client->get('http://localhost:3000/api/auth/verify', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token
                ]
            ]);

            $data = json_decode($response->getBody(), true);
            $user = $data['user'] ?? null;

            $map = [
                'admin' => 1,
                'finance' => 2,
                'organizer' => 3,
                'member' => 4
            ];

            if (!$user || $user['role_id'] !== $map[$requiredRole]) {
                abort(403, 'Akses ditolak: Anda tidak punya izin.');
            }

            session(['user' => $user]);
            return $next($request);

        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'Token tidak valid atau Node API tidak tersedia.');
        }
    };
};
