<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AttendeeResource;
use App\Models\Event;
use Illuminate\Http\Request;

class AttendeeController extends Controller
{

    public function index(Event $event) // http://127.0.0.1:8000/api/events/11/attendees -> this give you link to next pages
    {
        $attendees = $event->attendees()->latest();

        return AttendeeResource::collection(
            $attendees->paginate()
        );
    }


    public function store(Request $request, Event $event)
    {
        $attendee = $event->attendees()->create([
            'user_id' => 1
        ]);
    }


    public function show(string $id)
    {
        //
    }


    public function update(Request $request, string $id)
    {
        //
    }


    public function destroy(string $id)
    {
        //
    }
}
