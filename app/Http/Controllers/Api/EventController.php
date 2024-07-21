<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EventResource;
use App\Http\Traits\CanLoadRelationships;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    use CanLoadRelationships;

    // private readonly array $relations = ['user', 'attendees', 'attendees.user'];
    private array $relations = ['user', 'attendees', 'attendees.user'];

    public function index()
    {
        $query = $this->loadRelationships(Event::query());

        // return Event::all();
        return EventResource::collection($query->latest()->paginate());
    }  
    // http://127.0.0.1:8000/api/events?include=user,attendees,attendees.user // GET method // filter to load user and attendees like : http://127.0.0.1:8000/api/events?include=attendees


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
        return new EventResource($this->loadRelationships($event));
    } // http://127.0.0.1:8000/api/events?include=user

    public function show(Event $event)
    {
        return new EventResource($this->loadRelationships($event));
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
        return new EventResource($this->loadRelationships($event));

    }           

    public function destroy(Event $event)
    {
        $event->delete();

        // return response()->json(['massage' => 'Event deleted successfully']);
        return response(status: 204);
    }
}
