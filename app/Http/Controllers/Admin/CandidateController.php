<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CandidateProfile;
use App\Services\CvStorageService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CandidateController extends Controller
{
    public function __construct(
        private CvStorageService $cvStorageService
    ) {}

    public function index(Request $request): View
    {
        $query = CandidateProfile::with('user');

        if ($request->filled('search')) {
            $search = $request->string('search');
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($userQuery) use ($search) {
                    $userQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                })
                ->orWhere('university', 'like', "%{$search}%")
                ->orWhere('degree', 'like', "%{$search}%");
            });
        }

        if ($request->filled('university')) {
            $query->where('university', $request->string('university'));
        }

        if ($request->filled('degree')) {
            $query->where('degree', $request->string('degree'));
        }

        if ($request->filled('from_date')) {
            $query->where('created_at', '>=', $request->date('from_date'));
        }

        if ($request->filled('to_date')) {
            $query->where('created_at', '<=', $request->date('to_date'));
        }

        $sortBy = $request->string('sort_by', 'created_at')->toString();
        $sortDir = $request->string('sort_dir', 'desc')->toString();
        $query->orderBy($sortBy, $sortDir);

        $candidates = $query->paginate(15)->withQueryString();

        $stats = [
            'total' => CandidateProfile::count(),
            'with_cv' => CandidateProfile::whereNotNull('cv_path')->count(),
            'universities' => CandidateProfile::whereNotNull('university')
                ->where('university', '!=', '')
                ->distinct()
                ->count('university'),
            'new_this_week' => CandidateProfile::where('created_at', '>=', now()->startOfWeek())->count(),
        ];

        return view('admin.candidates.index', compact('candidates', 'stats'));
    }

    public function show(CandidateProfile $candidate): View
    {
        $candidate->load(['user', 'applications.position', 'applications.interviews']);

        return view('admin.candidates.show', compact('candidate'));
    }

    public function downloadCv(CandidateProfile $candidate): RedirectResponse
    {
        if (! $candidate->cv_path) {
            return back()->with('error', 'No CV available for this candidate.');
        }

        $url = $this->cvStorageService->getSignedUrl($candidate->cv_path);

        return redirect()->away($url);
    }
}
