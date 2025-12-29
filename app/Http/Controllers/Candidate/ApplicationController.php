<?php

namespace App\Http\Controllers\Candidate;

use App\Enums\ApplicationStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Candidate\CreateApplicationRequest;
use App\Http\Resources\ApplicationResource;
use App\Models\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApplicationController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $profile = $request->user()->candidateProfile;

        $applications = Application::where('candidate_id', $profile->id)
            ->with(['position', 'interviews'])
            ->latest()
            ->get();

        return response()->json([
            'applications' => ApplicationResource::collection($applications),
        ]);
    }

    public function store(CreateApplicationRequest $request): JsonResponse
    {
        $profile = $request->user()->candidateProfile;

        $existingApplication = Application::where('candidate_id', $profile->id)
            ->where('position_id', $request->position_id)
            ->first();

        if ($existingApplication) {
            return response()->json([
                'message' => 'You have already applied to this position',
            ], 422);
        }

        $application = DB::transaction(function () use ($request, $profile) {
            return Application::create([
                'candidate_id' => $profile->id,
                'position_id' => $request->position_id,
                'status' => ApplicationStatus::REGISTERED,
            ]);
        });

        return response()->json([
            'application' => new ApplicationResource($application->load('position')),
            'message' => 'Application submitted successfully',
        ], 201);
    }

    public function show(Request $request, Application $application): JsonResponse
    {
        $profile = $request->user()->candidateProfile;

        if ($application->candidate_id !== $profile->id) {
            abort(403, 'Unauthorized');
        }

        $application->load(['position', 'interviews']);

        return response()->json([
            'application' => new ApplicationResource($application),
        ]);
    }
}
