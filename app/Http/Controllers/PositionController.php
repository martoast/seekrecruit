<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Position;
use Illuminate\Contracts\View\View;

class PositionController extends Controller
{
    public function index(): View
    {
        $positions = Position::where('is_active', true)
            ->latest()
            ->get();

        return view('positions.index', compact('positions'));
    }

    public function show(Position $position): View
    {
        $hasApplied = false;
        $user = auth()->user();

        if ($user && $user->isCandidate() && $user->candidateProfile) {
            $hasApplied = Application::where('candidate_id', $user->candidateProfile->id)
                ->where('position_id', $position->id)
                ->exists();
        }

        return view('positions.show', compact('position', 'hasApplied'));
    }
}
