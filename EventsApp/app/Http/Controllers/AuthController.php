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
            
            if (!isset($data['token']) || !isset($data['user'])) {
                return back()->withErrors(['login' => 'Invalid response from authentication server']);
            }
            
            // Store JWT token and user data in session
            session([
                'jwt_token' => $data['token'],
                'user' => $data['user']
            ]);
            
            // Get role from user data
            $roleMap = [
                1 => 'admin.dashboard',
                2 => 'finance.dashboard',
                3 => 'organizer.dashboard',
                4 => 'member.dashboard'
            ];

            $route = $roleMap[$data['user']['role_id']] ?? 'dashboard';
            return redirect()->route($route);
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