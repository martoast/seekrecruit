<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\ScopesToClient;
use App\Http\Controllers\Controller;
use App\Models\CandidateProfile;
use App\Services\CvStorageService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CandidateController extends Controller
{
    use ScopesToClient;

    public function __construct(
        private CvStorageService $cvStorageService
    ) {}

    public function index(Request $request): View
    {
        $user = $request->user();
        $clientId = $this->activeClientId($user, $request->integer('client_id') ?: null);

        $query = CandidateProfile::with('user');

        // HR Admin can only see candidates who have applied to their client.
        // Super Admin filtering by client narrows the view the same way.
        if ($clientId) {
            $query->whereHas('applications.position', fn ($q) => $q->where('client_id', $clientId));
        }

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

        // KPIs — also scoped
        $baseStats = CandidateProfile::query();
        if ($clientId) {
            $baseStats->whereHas('applications.position', fn ($q) => $q->where('client_id', $clientId));
        }

        $stats = [
            'total' => (clone $baseStats)->count(),
            'with_cv' => (clone $baseStats)->whereNotNull('cv_path')->count(),
            'universities' => (clone $baseStats)->whereNotNull('university')
                ->where('university', '!=', '')
                ->distinct()
                ->count('university'),
            'new_this_week' => (clone $baseStats)->where('created_at', '>=', now()->startOfWeek())->count(),
        ];

        return view('admin.candidates.index', compact('candidates', 'stats'));
    }

    public function show(Request $request, CandidateProfile $candidate): View
    {
        $user = $request->user();

        // HR admins may only view candidates who applied to their client.
        // Only those applications are shown — cross-client ones are hidden.
        if ($user->isHrAdmin()) {
            $candidate->load([
                'user',
                'applications' => fn ($q) => $q->whereHas('position', fn ($pq) => $pq->where('client_id', $user->client_id)),
                'applications.position',
                'applications.interviews',
            ]);

            abort_if($candidate->applications->isEmpty(), 403);
        } else {
            $candidate->load(['user', 'applications.position.client', 'applications.interviews']);
        }

        return view('admin.candidates.show', compact('candidate'));
    }

    public function downloadCv(Request $request, CandidateProfile $candidate): RedirectResponse
    {
        $user = $request->user();

        if ($user->isHrAdmin()) {
            $hasLink = $candidate->applications()
                ->whereHas('position', fn ($q) => $q->where('client_id', $user->client_id))
                ->exists();

            abort_unless($hasLink, 403);
        }

        if (! $candidate->cv_path) {
            return back()->with('error', 'No CV available for this candidate.');
        }

        $url = $this->cvStorageService->getSignedUrl($candidate->cv_path);

        return redirect()->away($url);
    }
}
