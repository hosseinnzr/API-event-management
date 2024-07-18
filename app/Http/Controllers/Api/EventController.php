<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EventResource;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        // return Event::all();
        return EventResource::collection(Event::with('user')->get());
    }

    public function store(Request $request)
    {
        $event = Event::create([
            ...$request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'start_time' => 'required|date',
                'end_time' => 'required|date|after:start_time'
            ]),
            'user_id' => 1
        ]);

        // return $event;
        return new EventResource($event);
    }

    public function show(Event $event)
    {
        $event->load('user', 'attendees');
        return new EventResource($event);
    }

    public function update(Request $request, Event $event)
    {
        $event->update(
            $request->validate([
                'name' => 'sometimes|string|max:255',
                'description' => 'nullable|string',
                'start_time' => 'sometimes|date',
                'end_time' => 'sometimes|date|after:start_time'
            ])
        );

        // return $event;
        return new EventResource($event);

    }           

    public function destroy(Event $event)
    {
        $event->delete();

        // return response()->json(['massage' => 'Event deleted successfully']);
        return response(status: 204);
    }
}
