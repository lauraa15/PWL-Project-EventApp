<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $client = new Client();
        try {
            $response = $client->post('http://localhost:3000/api/auth/login', [
                'json' => [
                    'email' => $request->email,
                    'password' => $request->password,
                ]
            ]);
            $data = json_decode($response->getBody(), true);
            
            // Simpan token JWT ke session Laravel
            session(['jwt_token' => $data['token']]);
            
            // Simpan info user lain kalau perlu
            // session(['user' => $data['user']]);
            
            return redirect('/dashboard');
        } catch (\Exception $e) {
            return back()->withErrors(['login' => 'Login gagal, cek email dan password.']);
        }
    }
    
    public function logout()
    {
        session()->forget('jwt_token');
        // hapus session user juga kalau ada
        return redirect('/login');
    }
}