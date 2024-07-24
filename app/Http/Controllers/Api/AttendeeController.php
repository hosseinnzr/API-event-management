<?php

namespace App\Http\Controllers\Api;

use App\Models\Event;
use App\Models\Attendee;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use App\Http\Resources\AttendeeResource;
use App\Http\Traits\CanLoadRelationships;

class AttendeeController extends Controller
{
    use CanLoadRelationships;
    use AuthorizesRequests;

    public function __construct(){
        // $this->middleware('auth:sanctum')->except(['index', 'show', 'update]); not wok :(
        $this->authorizeResource(Event::class, 'attendee');
    }
    
    private array $relations = ['user'];

    public function index(Event $event) 
    {
        $attendees = $this->loadRelationships(
            $event->attendees()->latest()
        );

        return AttendeeResource::collection(
            $attendees->paginate()
        );
    } // http://127.0.0.1:8000/api/events/11/attendees -> this give you link to next pages


    public function store(Request $request, Event $event)
    {
        $attendee = $this->loadRelationships(
            $event->attendees()->create([
                'user_id' => 1
            ])
        );

        return new AttendeeResource($attendee);
    } // http://127.0.0.1:8000/api/events/10/attendees?include=user


    public function show(Event $event, Attendee $attendee)
    {
        return new AttendeeResource(
            $this->loadRelationships($attendee)
        );
    }


    public function update(Request $request, Event $event)
    {
        //
    }


    public function destroy(Event $event, Attendee $attendee)
    {
        if(Gate::denies("delete-attendee", [$event, $attendee])){
            abort(403, 'You are not authorized to detele this attendee.');
        }
        
        $attendee->delete();

        return response(status: 204);
    }
}
