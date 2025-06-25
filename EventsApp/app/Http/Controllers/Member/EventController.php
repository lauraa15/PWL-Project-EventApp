<?php

namespace App\Http\Controllers\Member;

use App\Services\ApiService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    protected $apiService;

    public function __construct(ApiService $apiService)
    {
        $this->apiService = $apiService;
    }
    public function index()
    {
        $response = $this->apiService->get('events');

        if (isset($response['error'])) {
            return back()->with('error', $response['error']);
        }

        $events = $response['events'] ?? [];
        $eventTypes = $response['eventTypes'] ?? [];

        return view('roles.member.dashboard', compact('events', 'eventTypes'));
    }
    public function register(Request $request, $eventId)
    {
        dd($request);
        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'nullable|string'
        ]);

        // Sertakan user ID jika menggunakan auth
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'Please login first.');
        }

        $payload = [
            'name'        => $data['name'],
            'email'       => $data['email'],
            'phone'       => $data['phone'] ?? null,
        ];

        // Kirim ke API Node.js
        $response = $this->apiService->post("events/{$eventId}/register", $payload);

        if (isset($response['error'])) {
            return back()->with('error', $response['error']);
        }

        return redirect()->route('member.events.index')->with('success', 'Registration successful!');
    }
}
