<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PositionRequest;
use App\Http\Resources\PositionResource;
use App\Models\Position;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PositionController extends Controller
{
    public function index(): JsonResponse
    {
        $positions = Position::latest()->get();

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
        // Delete associated images
        if ($position->image) {
            Storage::disk('public')->delete('position-images/' . $position->image);
        }
        if ($position->company_logo) {
            Storage::disk('public')->delete('company-logos/' . $position->company_logo);
        }

        $position->delete();

        return response()->json([
            'message' => 'Position deleted successfully',
        ]);
    }

    public function uploadImage(Request $request, Position $position): JsonResponse
    {
        $request->validate([
            'image' => ['required', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
        ]);

        // Delete old image if exists
        if ($position->image) {
            Storage::disk('public')->delete('position-images/' . $position->image);
        }

        $file = $request->file('image');
        $filename = 'position_' . $position->id . '_' . time() . '.' . $file->getClientOriginalExtension();
        $file->storeAs('position-images', $filename, 'public');

        $position->update(['image' => $filename]);

        return response()->json([
            'message' => 'Position image uploaded successfully',
            'position' => new PositionResource($position->fresh()),
        ]);
    }

    public function deleteImage(Position $position): JsonResponse
    {
        if (!$position->image) {
            return response()->json(['message' => 'No image to delete'], 404);
        }

        Storage::disk('public')->delete('position-images/' . $position->image);
        $position->update(['image' => null]);

        return response()->json([
            'message' => 'Position image deleted successfully',
            'position' => new PositionResource($position->fresh()),
        ]);
    }

    public function uploadCompanyLogo(Request $request, Position $position): JsonResponse
    {
        $request->validate([
            'company_logo' => ['required', 'image', 'mimes:jpeg,png,jpg,webp,svg', 'max:2048'],
        ]);

        // Delete old logo if exists
        if ($position->company_logo) {
            Storage::disk('public')->delete('company-logos/' . $position->company_logo);
        }

        $file = $request->file('company_logo');
        $filename = 'company_' . $position->id . '_' . time() . '.' . $file->getClientOriginalExtension();
        $file->storeAs('company-logos', $filename, 'public');

        $position->update(['company_logo' => $filename]);

        return response()->json([
            'message' => 'Company logo uploaded successfully',
            'position' => new PositionResource($position->fresh()),
        ]);
    }

    public function deleteCompanyLogo(Position $position): JsonResponse
    {
        if (!$position->company_logo) {
            return response()->json(['message' => 'No company logo to delete'], 404);
        }

        Storage::disk('public')->delete('company-logos/' . $position->company_logo);
        $position->update(['company_logo' => null]);

        return response()->json([
            'message' => 'Company logo deleted successfully',
            'position' => new PositionResource($position->fresh()),
        ]);
    }
}
