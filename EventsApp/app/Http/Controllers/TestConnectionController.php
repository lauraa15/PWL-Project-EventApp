<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

class TestConnectionController extends Controller
{
    public function test()
    {
        try {
        // kode test koneksi
        return response()->json(['message' => 'Laravel API is working ']);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
        // $response = Http::get(config('app.api_url').'/test-connection');

        // return response()->json([
        //     'backend_response' => $response->json(),
        //     'frontend_status' => 'Laravel is working!',
        //     'connection_status' => $response->successful() ? 'CONNECTED' : 'FAILED'
        // ]);
    }
}
