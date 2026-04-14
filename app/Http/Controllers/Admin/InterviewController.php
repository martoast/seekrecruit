<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CreateInterviewRequest;
use App\Http\Requests\Admin\UpdateInterviewRequest;
use App\Models\Application;
use App\Models\Interview;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class InterviewController extends Controller
{
    public function index(Request $request): View
    {
        $query = Interview::with(['application.candidate.user', 'application.position']);

        if ($request->filled('from_date')) {
            $query->where('scheduled_at', '>=', $request->date('from_date'));
        }

        if ($request->filled('to_date')) {
            $query->where('scheduled_at', '<=', $request->date('to_date'));
        }

        if ($request->filled('type')) {
            $query->where('type', $request->string('type'));
        }

        $interviews = $query->orderBy('scheduled_at')->get();

        $applications = Application::with(['candidate.user', 'position'])
            ->latest()
            ->take(100)
            ->get();

        return view('admin.interviews.index', compact('interviews', 'applications'));
    }

    public function store(CreateInterviewRequest $request): RedirectResponse
    {
        Interview::create($request->validated());

        return redirect()->route('admin.interviews.index')
            ->with('success', 'Interview scheduled successfully.');
    }

    public function update(UpdateInterviewRequest $request, Interview $interview): RedirectResponse
    {
        $interview->update($request->validated());

        return redirect()->route('admin.interviews.index')
            ->with('success', 'Interview updated successfully.');
    }

    public function destroy(Interview $interview): RedirectResponse
    {
        $interview->delete();

        return back()->with('success', 'Interview cancelled successfully.');
    }
}
