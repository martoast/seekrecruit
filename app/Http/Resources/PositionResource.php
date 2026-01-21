<?php

namespace App\Http\Resources;

use App\Enums\UserRole;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class PositionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'requirements' => $this->requirements,
            'location' => $this->location,
            'is_active' => $this->is_active,
            'image_url' => $this->image_url,
            'company_name' => $this->company_name,
            'company_logo_url' => $this->company_logo_url,
            'created_at' => $this->created_at->toISOString(),
            'updated_at' => $this->updated_at->toISOString(),
        ];

        // Try to get user from sanctum guard (works even without auth middleware)
        $user = Auth::guard('sanctum')->user();
        if ($user && $user->role === UserRole::CANDIDATE && $user->candidateProfile) {
            $data['has_applied'] = $this->applications()
                ->where('candidate_id', $user->candidateProfile->id)
                ->exists();
        }

        return $data;
    }
}
