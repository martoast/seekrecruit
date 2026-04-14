<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $profile = $user->candidateProfile;

        $applications = $profile
            ? $profile->applications()->with(['position', 'interviews'])->latest()->get()
            : collect();

        $recentApplications = $applications->take(3);

        $profileFields = $profile ? [
            $profile->university,
            $profile->degree,
            $profile->location,
            $profile->phone,
            $profile->bio,
            $profile->cv_path,
            is_array($profile->skills) && count($profile->skills) > 0,
        ] : [];

        $profileProgress = count($profileFields)
            ? (int) round(collect($profileFields)->filter()->count() / count($profileFields) * 100)
            : 0;

        return view('candidate.dashboard', compact(
            'user',
            'profile',
            'applications',
            'recentApplications',
            'profileProgress'
        ));
    }
}
