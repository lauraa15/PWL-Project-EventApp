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
        $eventPayments = $response['eventPayments'] ?? [];

        return view('roles.member.dashboard', compact('events', 'eventTypes', 'eventPayments'));
    }
}
