<?php

namespace App\Http\Controllers\Candidate;

use App\Enums\ApplicationStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Candidate\CreateApplicationRequest;
use App\Models\Application;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApplicationController extends Controller
{
    public function index(Request $request): View
    {
        $profile = $request->user()->candidateProfile;

        $applications = $profile
            ? Application::where('candidate_id', $profile->id)
                ->with(['position', 'interviews'])
                ->latest()
                ->get()
            : collect();

        return view('candidate.applications.index', compact('applications'));
    }

    public function store(CreateApplicationRequest $request): RedirectResponse
    {
        $profile = $request->user()->candidateProfile;

        $existing = Application::where('candidate_id', $profile->id)
            ->where('position_id', $request->integer('position_id'))
            ->first();

        if ($existing) {
            return back()->with('error', 'You have already applied to this position.');
        }

        $application = DB::transaction(fn () => Application::create([
            'candidate_id' => $profile->id,
            'position_id' => $request->integer('position_id'),
            'status' => ApplicationStatus::REGISTERED,
        ]));

        return redirect()
            ->route('candidate.applications.show', $application)
            ->with('success', 'Application submitted successfully!');
    }

    public function show(Request $request, Application $application): View
    {
        $profile = $request->user()->candidateProfile;

        abort_unless($application->candidate_id === $profile?->id, 403);

        $application->load(['position', 'interviews']);

        return view('candidate.applications.show', compact('application'));
    }
}
