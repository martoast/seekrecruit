<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\CandidateProfileResource;
use App\Models\CandidateProfile;
use App\Services\CvStorageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CandidateController extends Controller
{
    public function __construct(
        private CvStorageService $cvStorageService
    ) {}

    public function index(Request $request): JsonResponse
    {
        $query = CandidateProfile::with('user');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($userQuery) use ($search) {
                    $userQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                })
                ->orWhereRaw('JSON_CONTAINS(skills, ?)', [json_encode($search)]);
            });
        }

        if ($request->filled('university')) {
            $query->where('university', $request->university);
        }

        if ($request->filled('degree')) {
            $query->where('degree', $request->degree);
        }

        if ($request->filled('status')) {
            $query->whereHas('applications', function ($q) use ($request) {
                $q->where('status', $request->status);
            });
        }

        if ($request->filled('from_date')) {
            $query->where('created_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->where('created_at', '<=', $request->to_date);
        }

        $sortBy = $request->get('sort_by', 'created_at');
        $sortDir = $request->get('sort_dir', 'desc');
        $query->orderBy($sortBy, $sortDir);

        $perPage = $request->get('per_page', 15);
        $candidates = $query->paginate($perPage);

        return response()->json([
            'candidates' => CandidateProfileResource::collection($candidates),
            'meta' => [
                'current_page' => $candidates->currentPage(),
                'last_page' => $candidates->lastPage(),
                'per_page' => $candidates->perPage(),
                'total' => $candidates->total(),
            ],
        ]);
    }

    public function show(CandidateProfile $candidate): JsonResponse
    {
        $candidate->load(['user', 'applications.position', 'applications.interviews']);

        return response()->json([
            'candidate' => new CandidateProfileResource($candidate),
        ]);
    }

    public function downloadCv(CandidateProfile $candidate): JsonResponse
    {
        if (! $candidate->cv_path) {
            return response()->json([
                'message' => 'No CV available for this candidate',
            ], 404);
        }

        $url = $this->cvStorageService->getSignedUrl($candidate->cv_path);

        return response()->json([
            'url' => $url,
        ]);
    }
}
