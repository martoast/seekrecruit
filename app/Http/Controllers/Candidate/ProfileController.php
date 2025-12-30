<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use App\Http\Requests\Candidate\UpdateProfileRequest;
use App\Http\Requests\Candidate\UploadCvRequest;
use App\Http\Requests\Candidate\UploadProfileImageRequest;
use App\Http\Resources\CandidateProfileResource;
use App\Services\CvStorageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function __construct(
        private CvStorageService $cvStorageService
    ) {}

    public function show(Request $request): JsonResponse
    {
        $profile = $request->user()->candidateProfile;

        return response()->json([
            'profile' => new CandidateProfileResource($profile),
        ]);
    }

    public function update(UpdateProfileRequest $request): JsonResponse
    {
        $profile = $request->user()->candidateProfile;
        $profile->update($request->validated());

        return response()->json([
            'profile' => new CandidateProfileResource($profile->fresh()),
        ]);
    }

    public function uploadCv(UploadCvRequest $request): JsonResponse
    {
        $profile = $request->user()->candidateProfile;

        if ($profile->cv_path) {
            $this->cvStorageService->delete($profile->cv_path);
        }

        $path = $this->cvStorageService->upload(
            $request->file('cv'),
            $request->user()->id
        );

        $profile->update(['cv_path' => $path]);

        return response()->json([
            'message' => 'CV uploaded successfully',
            'profile' => new CandidateProfileResource($profile->fresh()),
        ]);
    }

    public function deleteCv(Request $request): JsonResponse
    {
        $profile = $request->user()->candidateProfile;

        if (! $profile->cv_path) {
            return response()->json([
                'message' => 'No CV to delete',
            ], 404);
        }

        $this->cvStorageService->delete($profile->cv_path);
        $profile->update(['cv_path' => null]);

        return response()->json([
            'message' => 'CV deleted successfully',
        ]);
    }

    public function downloadCv(Request $request): JsonResponse
    {
        $profile = $request->user()->candidateProfile;

        if (! $profile->cv_path) {
            return response()->json([
                'message' => 'No CV available',
            ], 404);
        }

        $url = $this->cvStorageService->getSignedUrl($profile->cv_path);

        return response()->json([
            'url' => $url,
        ]);
    }

    public function uploadProfileImage(UploadProfileImageRequest $request): JsonResponse
    {
        $profile = $request->user()->candidateProfile;

        // Delete old image if exists
        if ($profile->profile_image) {
            Storage::disk('public')->delete('profile-images/' . $profile->profile_image);
        }

        $file = $request->file('profile_image');
        $filename = 'profile_' . $request->user()->id . '_' . time() . '.' . $file->getClientOriginalExtension();
        $file->storeAs('profile-images', $filename, 'public');

        $profile->update(['profile_image' => $filename]);

        return response()->json([
            'message' => 'Profile image uploaded successfully',
            'profile' => new CandidateProfileResource($profile->fresh()),
        ]);
    }

    public function deleteProfileImage(Request $request): JsonResponse
    {
        $profile = $request->user()->candidateProfile;

        if (! $profile->profile_image) {
            return response()->json([
                'message' => 'No profile image to delete',
            ], 404);
        }

        Storage::disk('public')->delete('profile-images/' . $profile->profile_image);
        $profile->update(['profile_image' => null]);

        return response()->json([
            'message' => 'Profile image deleted successfully',
            'profile' => new CandidateProfileResource($profile->fresh()),
        ]);
    }
}
