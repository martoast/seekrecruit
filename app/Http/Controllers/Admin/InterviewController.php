<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CreateInterviewRequest;
use App\Http\Requests\Admin\UpdateInterviewRequest;
use App\Http\Resources\InterviewResource;
use App\Models\Interview;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InterviewController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Interview::with('application.candidate.user', 'application.position');

        if ($request->filled('from_date')) {
            $query->where('scheduled_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->where('scheduled_at', '<=', $request->to_date);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $interviews = $query->orderBy('scheduled_at', 'asc')->get();

        return response()->json([
            'interviews' => InterviewResource::collection($interviews),
        ]);
    }

    public function store(CreateInterviewRequest $request): JsonResponse
    {
        $interview = Interview::create($request->validated());

        return response()->json([
            'interview' => new InterviewResource($interview),
            'message' => 'Interview scheduled successfully',
        ], 201);
    }

    public function update(UpdateInterviewRequest $request, Interview $interview): JsonResponse
    {
        $interview->update($request->validated());

        return response()->json([
            'interview' => new InterviewResource($interview->fresh()),
            'message' => 'Interview updated successfully',
        ]);
    }

    public function destroy(Interview $interview): JsonResponse
    {
        $interview->delete();

        return response()->json([
            'message' => 'Interview cancelled successfully',
        ]);
    }
}
