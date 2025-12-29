<?php

namespace App\Services;

use App\Models\Application;
use App\Models\CandidateProfile;
use App\Models\Interview;
use Illuminate\Support\Facades\DB;

class StatsService
{
    public function getStats(): array
    {
        $totalCandidates = CandidateProfile::count();
        $totalApplications = Application::count();

        $applicationsByStatus = Application::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $statusCounts = [];
        $statuses = ['registered', 'preselected', 'interview', 'evaluation', 'finalist', 'hired', 'discarded'];
        foreach ($statuses as $status) {
            $statusCounts[$status] = $applicationsByStatus[$status] ?? 0;
        }

        $interviewsThisWeek = Interview::whereBetween('scheduled_at', [
            now()->startOfWeek(),
            now()->endOfWeek()
        ])->count();

        $recentApplications = Application::with(['candidate.user', 'position'])
            ->latest()
            ->take(5)
            ->get();

        $topUniversities = CandidateProfile::select('university', DB::raw('count(*) as count'))
            ->whereNotNull('university')
            ->where('university', '!=', '')
            ->groupBy('university')
            ->orderByDesc('count')
            ->take(10)
            ->get()
            ->map(function ($item) {
                return [
                    'name' => $item->university,
                    'count' => $item->count,
                ];
            })
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
