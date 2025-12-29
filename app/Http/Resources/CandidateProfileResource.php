<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CandidateProfileResource extends JsonResource
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
            'user' => new UserResource($this->whenLoaded('user')),
            'university' => $this->university,
            'degree' => $this->degree,
            'semester' => $this->semester,
            'graduation_year' => $this->graduation_year,
            'skills' => $this->skills,
            'location' => $this->location,
            'age' => $this->age,
            'gender' => $this->gender?->value,
            'phone' => $this->phone,
            'linkedin_url' => $this->linkedin_url,
            'bio' => $this->bio,
            'has_cv' => !is_null($this->cv_path),
            'created_at' => $this->created_at->toISOString(),
            'updated_at' => $this->updated_at->toISOString(),
        ];
    }
}
