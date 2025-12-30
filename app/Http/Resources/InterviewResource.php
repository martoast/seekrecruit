<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InterviewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'application_id' => $this->application_id,
            'scheduled_at' => $this->scheduled_at->toISOString(),
            'location' => $this->location,
            'type' => $this->type->value,
            'notes' => $this->notes,
            'created_at' => $this->created_at->toISOString(),
            'updated_at' => $this->updated_at->toISOString(),
            'application' => $this->whenLoaded('application', function () {
                return [
                    'id' => $this->application->id,
                    'status' => $this->application->status->value,
                    'candidate' => $this->application->candidate ? [
                        'id' => $this->application->candidate->id,
                        'user' => $this->application->candidate->user ? [
                            'id' => $this->application->candidate->user->id,
                            'name' => $this->application->candidate->user->name,
                            'email' => $this->application->candidate->user->email,
                        ] : null,
                    ] : null,
                    'position' => $this->application->position ? [
                        'id' => $this->application->position->id,
                        'title' => $this->application->position->title,
                        'location' => $this->application->position->location,
                    ] : null,
                ];
            }),
        ];
    }
}
