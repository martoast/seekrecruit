<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PositionRequest;
use App\Http\Resources\PositionResource;
use App\Models\Position;
use Illuminate\Http\JsonResponse;

class PositionController extends Controller
{
    public function index(): JsonResponse
    {
        $positions = Position::latest()->get();

        return response()->json([
            'positions' => PositionResource::collection($positions),
        ]);
    }

    public function store(PositionRequest $request): JsonResponse
    {
        $position = Position::create($request->validated());

        return response()->json([
            'position' => new PositionResource($position),
            'message' => 'Position created successfully',
        ], 201);
    }

    public function update(PositionRequest $request, Position $position): JsonResponse
    {
        $position->update($request->validated());

        return response()->json([
            'position' => new PositionResource($position->fresh()),
            'message' => 'Position updated successfully',
        ]);
    }

    public function destroy(Position $position): JsonResponse
    {
        $position->delete();

        return response()->json([
            'message' => 'Position deleted successfully',
        ]);
    }
}
