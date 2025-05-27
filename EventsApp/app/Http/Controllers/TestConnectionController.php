<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

class TestConnectionController extends Controller
{
    public function test()
    {
        $response = Http::get(config('app.api_url').'/test-connection');
        
        return response()->json([
            'backend_response' => $response->json(),
            'frontend_status' => 'Laravel is working!',
            'connection_status' => $response->successful() ? 'CONNECTED' : 'FAILED'
        ]);
    }
}