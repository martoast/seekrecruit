<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\ScopesToClient;
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
    use ScopesToClient;

    public function index(Request $request): View
    {
        $user = $request->user();
        $clientId = $this->activeClientId($user, $request->integer('client_id') ?: null);

        $query = Interview::with(['application.candidate.user', 'application.position.client']);

        if ($clientId) {
            $query->whereHas('application.position', fn ($q) => $q->where('client_id', $clientId));
        }

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

        // Applications dropdown for the modal — scoped too
        $appsQuery = Application::with(['candidate.user', 'position'])->latest()->take(100);

        if ($clientId) {
            $appsQuery->whereHas('position', fn ($q) => $q->where('client_id', $clientId));
        }

        $applications = $appsQuery->get();

        return view('admin.interviews.index', compact('interviews', 'applications'));
    }

    public function store(CreateInterviewRequest $request): RedirectResponse
    {
        $application = Application::with('position')->findOrFail($request->integer('application_id'));
        abort_unless($this->userOwnsClient($request->user(), $application->position?->client_id), 403);

        Interview::create($request->validated());

        return redirect()->route('admin.interviews.index')
            ->with('success', 'Interview scheduled successfully.');
    }

    public function update(UpdateInterviewRequest $request, Interview $interview): RedirectResponse
    {
        $interview->load('application.position');
        abort_unless($this->userOwnsClient($request->user(), $interview->application?->position?->client_id), 403);

        $interview->update($request->validated());

        return redirect()->route('admin.interviews.index')
            ->with('success', 'Interview updated successfully.');
    }

    public function destroy(Request $request, Interview $interview): RedirectResponse
    {
        $interview->load('application.position');
        abort_unless($this->userOwnsClient($request->user(), $interview->application?->position?->client_id), 403);

        $interview->delete();

        return back()->with('success', 'Interview cancelled successfully.');
    }
}
