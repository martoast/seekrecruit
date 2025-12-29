<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use App\Http\Requests\Candidate\UpdateProfileRequest;
use App\Http\Requests\Candidate\UploadCvRequest;
use App\Http\Resources\CandidateProfileResource;
use App\Services\CvStorageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
}
