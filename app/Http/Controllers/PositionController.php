<?php

namespace App\Http\Controllers;

use App\Http\Resources\PositionResource;
use App\Models\Position;
use Illuminate\Http\JsonResponse;

class PositionController extends Controller
{
    public function index(): JsonResponse
    {
        $positions = Position::where('is_active', true)
            ->latest()
            ->get();

        return response()->json([
            'positions' => PositionResource::collection($positions),
        ]);
    }

    public function show(Position $position): JsonResponse
    {
        return response()->json([
            'position' => new PositionResource($position),
        ]);
    }
}
