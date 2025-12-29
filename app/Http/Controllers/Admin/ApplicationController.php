<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CreateNoteRequest;
use App\Http\Requests\Admin\UpdateApplicationStatusRequest;
use App\Http\Resources\ApplicationResource;
use App\Http\Resources\NoteResource;
use App\Models\Application;
use App\Models\ApplicationNote;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Application::with(['candidate.user', 'position']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('position_id')) {
            $query->where('position_id', $request->position_id);
        }

        if ($request->filled('from_date')) {
            $query->where('created_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->where('created_at', '<=', $request->to_date);
        }

        $perPage = $request->get('per_page', 15);
        $applications = $query->latest()->paginate($perPage);

        return response()->json([
            'applications' => ApplicationResource::collection($applications),
            'meta' => [
                'current_page' => $applications->currentPage(),
                'last_page' => $applications->lastPage(),
                'per_page' => $applications->perPage(),
                'total' => $applications->total(),
            ],
        ]);
    }

    public function show(Application $application): JsonResponse
    {
        $application->load(['candidate.user', 'position', 'interviews', 'notes.author']);

        return response()->json([
            'application' => new ApplicationResource($application),
        ]);
    }

    public function updateStatus(UpdateApplicationStatusRequest $request, Application $application): JsonResponse
    {
        $application->update([
            'status' => $request->status,
        ]);

        return response()->json([
            'application' => new ApplicationResource($application->load(['candidate.user', 'position'])),
            'message' => 'Application status updated successfully',
        ]);
    }

    public function addNote(CreateNoteRequest $request, Application $application): JsonResponse
    {
        $note = ApplicationNote::create([
            'application_id' => $application->id,
            'author_id' => $request->user()->id,
            'content' => $request->content,
            'created_at' => now(),
        ]);

        return response()->json([
            'note' => new NoteResource($note->load('author')),
            'message' => 'Note added successfully',
        ], 201);
    }

    public function getNotes(Application $application): JsonResponse
    {
        $notes = $application->notes()->with('author')->latest('created_at')->get();

        return response()->json([
            'notes' => NoteResource::collection($notes),
        ]);
    }
}
