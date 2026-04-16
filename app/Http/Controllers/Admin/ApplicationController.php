<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\ScopesToClient;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CreateNoteRequest;
use App\Http\Requests\Admin\UpdateApplicationStatusRequest;
use App\Models\Application;
use App\Models\ApplicationNote;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApplicationController extends Controller
{
    use ScopesToClient;

    public function index(Request $request): View
    {
        $user = $request->user();
        $clientId = $this->activeClientId($user, $request->integer('client_id') ?: null);

        $query = Application::with(['candidate.user', 'position.client']);

        if ($clientId) {
            $query->whereHas('position', fn ($q) => $q->where('client_id', $clientId));
        }

        if ($request->filled('search')) {
            $search = $request->string('search');
            $query->whereHas('candidate.user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            })->orWhereHas('position', function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }

        if ($request->filled('position_id')) {
            $query->where('position_id', $request->integer('position_id'));
        }

        if ($request->filled('from_date')) {
            $query->where('created_at', '>=', $request->date('from_date'));
        }

        if ($request->filled('to_date')) {
            $query->where('created_at', '<=', $request->date('to_date'));
        }

        $applications = $query->latest()->paginate(15)->withQueryString();

        // Stats are scoped the same way as the list
        $statsQuery = Application::query();
        if ($clientId) {
            $statsQuery->whereHas('position', fn ($q) => $q->where('client_id', $clientId));
        }

        $byStatus = $statsQuery->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $stats = [
            'total' => array_sum($byStatus),
            'pending' => ($byStatus['registered'] ?? 0) + ($byStatus['preselected'] ?? 0),
            'in_interview' => $byStatus['interview'] ?? 0,
            'hired' => $byStatus['hired'] ?? 0,
        ];

        return view('admin.applications.index', compact('applications', 'stats'));
    }

    public function show(Request $request, Application $application): View
    {
        $application->load(['candidate.user', 'position.client', 'interviews', 'notes.author']);

        abort_unless($this->userOwnsClient($request->user(), $application->position?->client_id), 403);

        return view('admin.applications.show', compact('application'));
    }

    public function updateStatus(UpdateApplicationStatusRequest $request, Application $application): RedirectResponse
    {
        $application->load('position');
        abort_unless($this->userOwnsClient($request->user(), $application->position?->client_id), 403);

        $application->update(['status' => $request->string('status')]);

        return back()->with('success', 'Application status updated successfully.');
    }

    public function addNote(CreateNoteRequest $request, Application $application): RedirectResponse
    {
        $application->load('position');
        abort_unless($this->userOwnsClient($request->user(), $application->position?->client_id), 403);

        ApplicationNote::create([
            'application_id' => $application->id,
            'author_id' => $request->user()->id,
            'content' => $request->string('content'),
            'created_at' => now(),
        ]);

        return back()->with('success', 'Note added successfully.');
    }
}
