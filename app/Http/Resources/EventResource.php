<?php

namespace App\Http\Resources;

use App\Models\Attendee;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\Http\Resources\AttendeeResource;
use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'discription' => $this->discription,
            'start_time' => $this->start_time,
            'end_time' => $this->start_time,
            'user' => new UserResource($this->whenLoaded('user')),
            'attendees' => AttendeeResource::collection(
                $this->whenLoaded('attendees')
            )
        ];
    }
}
