<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ApiService;

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

        return view('roles.organizer.dashboard', compact('events', 'eventTypes'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'event_type_id' => 'required|integer',
            'name' => 'required|string',
            'description' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'location' => 'required|string',
            'registration_fee' => 'nullable|numeric',
            'registration_type' => 'required|in:event_only,session_only,both',
            'max_participants' => 'required|integer|min:1',
            'current_participants' => 'nullable|integer|min:0',
            'registration_open_date' => 'required|date',
            'registration_close_date' => 'required|date|after_or_equal:registration_open_date',
            'certificate_type' => 'required|in:per_session,per_event',
            'is_active' => 'required|boolean',
        ]);

        // Handle file upload (optional)
        if ($request->hasFile('poster_image')) {
            $image = $request->file('poster_image');
            $imageContent = base64_encode(file_get_contents($image));
            $data['poster_image'] = $imageContent; // Kirim base64 string ke Node.js
        } else {
            $data['poster_image'] = null;
        }

        // Kirim ke API Node.js
        $response = $this->apiService->post('events', $data);

        if (isset($response['error'])) {
            return back()->with('error', $response['error']);
        }

        return redirect()->route('events.index')->with('success', 'Event created successfully');
    }
}
