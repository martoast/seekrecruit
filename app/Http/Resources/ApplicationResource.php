<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ApplicationResource extends JsonResource
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
            'candidate' => new CandidateProfileResource($this->whenLoaded('candidate')),
            'position' => new PositionResource($this->whenLoaded('position')),
            'status' => $this->status->value,
            'interviews' => InterviewResource::collection($this->whenLoaded('interviews')),
            'notes' => NoteResource::collection($this->whenLoaded('notes')),
            'created_at' => $this->created_at->toISOString(),
            'updated_at' => $this->updated_at->toISOString(),
        ];
    }
}
