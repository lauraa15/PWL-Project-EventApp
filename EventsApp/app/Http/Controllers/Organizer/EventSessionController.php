<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventSession;
use Illuminate\Http\Request;

class EventSessionController extends Controller
{
    public function index(Event $event)
    {
        $this->authorize('update', $event);
        
        $sessions = $event->sessions()->orderBy('start_time')->get();
        return view('organizer.events.sessions.index', compact('event', 'sessions'));
    }

    public function store(Request $request, Event $event)
    {
        $this->authorize('update', $event);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'required|date_format:Y-m-d H:i:s',
            'end_time' => 'required|date_format:Y-m-d H:i:s|after:start_time',
            'location' => 'required|string|max:255',
            'speaker' => 'nullable|string|max:255',
            'max_participants' => 'nullable|integer|min:1'
        ]);

        $session = $event->sessions()->create($request->all());

        return response()->json([
            'message' => 'Session created successfully',
            'session' => $session
        ]);
    }

    public function update(Request $request, Event $event, EventSession $session)
    {
        $this->authorize('update', $event);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'required|date_format:Y-m-d H:i:s',
            'end_time' => 'required|date_format:Y-m-d H:i:s|after:start_time',
            'location' => 'required|string|max:255',
            'speaker' => 'nullable|string|max:255',
            'max_participants' => 'nullable|integer|min:1'
        ]);

        $session->update($request->all());

        return response()->json([
            'message' => 'Session updated successfully',
            'session' => $session
        ]);
    }

    public function destroy(Event $event, EventSession $session)
    {
        $this->authorize('update', $event);
        
        $session->delete();
        
        return response()->json([
            'message' => 'Session deleted successfully'
        ]);
    }
}
