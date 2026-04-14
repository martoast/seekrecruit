<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CreateNoteRequest;
use App\Http\Requests\Admin\UpdateApplicationStatusRequest;
use App\Models\Application;
use App\Models\ApplicationNote;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    public function index(Request $request): View
    {
        $query = Application::with(['candidate.user', 'position']);

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

        return view('admin.applications.index', compact('applications'));
    }

    public function show(Application $application): View
    {
        $application->load(['candidate.user', 'position', 'interviews', 'notes.author']);

        return view('admin.applications.show', compact('application'));
    }

    public function updateStatus(UpdateApplicationStatusRequest $request, Application $application): RedirectResponse
    {
        $application->update(['status' => $request->string('status')]);

        return back()->with('success', 'Application status updated successfully.');
    }

    public function addNote(CreateNoteRequest $request, Application $application): RedirectResponse
    {
        ApplicationNote::create([
            'application_id' => $application->id,
            'author_id' => $request->user()->id,
            'content' => $request->string('content'),
            'created_at' => now(),
        ]);

        return back()->with('success', 'Note added successfully.');
    }
}
