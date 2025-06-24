<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

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

        $events = $response['data'];
        return view('events.index', compact('events'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'description' => 'required',
            // other validation rules
        ]);

        $response = $this->apiService->post('events', $data);

        if (isset($response['error'])) {
            return back()->with('error', $response['error']);
        }

        return redirect()->route('events.index')->with('success', 'Event created successfully');
    }
}
