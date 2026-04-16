<?php

namespace App\Services;

use App\Models\Application;
use App\Models\CandidateProfile;
use App\Models\Interview;
use Illuminate\Support\Facades\DB;

class StatsService
{
    /**
     * @param  int|null  $clientId  When set, all stats scope to a single client
     *                              (positions belonging to that client and their
     *                              applications/interviews/candidates). Null = platform-wide.
     */
    public function getStats(?int $clientId = null): array
    {
        $applicationsScope = function ($query) use ($clientId) {
            if ($clientId) {
                $query->whereHas('position', fn ($q) => $q->where('client_id', $clientId));
            }
        };

        $candidatesScope = function ($query) use ($clientId) {
            if ($clientId) {
                $query->whereHas('applications.position', fn ($q) => $q->where('client_id', $clientId));
            }
        };

        $interviewsScope = function ($query) use ($clientId) {
            if ($clientId) {
                $query->whereHas('application.position', fn ($q) => $q->where('client_id', $clientId));
            }
        };

        $totalCandidates = CandidateProfile::query()->tap($candidatesScope)->count();

        $totalApplications = Application::query()->tap($applicationsScope)->count();

        $applicationsByStatus = Application::query()
            ->tap($applicationsScope)
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $statuses = ['registered', 'preselected', 'interview', 'evaluation', 'finalist', 'hired', 'discarded'];
        $statusCounts = [];
        foreach ($statuses as $status) {
            $statusCounts[$status] = $applicationsByStatus[$status] ?? 0;
        }

        $interviewsThisWeek = Interview::query()
            ->tap($interviewsScope)
            ->whereBetween('scheduled_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->count();

        $recentApplications = Application::with(['candidate.user', 'position.client'])
            ->tap($applicationsScope)
            ->latest()
            ->take(5)
            ->get();

        $topUniversities = CandidateProfile::query()
            ->tap($candidatesScope)
            ->select('university', DB::raw('count(*) as count'))
            ->whereNotNull('university')
            ->where('university', '!=', '')
            ->groupBy('university')
            ->orderByDesc('count')
            ->take(10)
            ->get()
            ->map(fn ($item) => ['name' => $item->university, 'count' => $item->count])
            ->toArray();

        return [
            'total_candidates' => $totalCandidates,
            'total_applications' => $totalApplications,
            'applications_by_status' => $statusCounts,
            'interviews_this_week' => $interviewsThisWeek,
            'recent_applications' => $recentApplications,
            'top_universities' => $topUniversities,
        ];
    }
}
