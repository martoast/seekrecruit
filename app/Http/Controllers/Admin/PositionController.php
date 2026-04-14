<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PositionRequest;
use App\Models\Position;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PositionController extends Controller
{
    public function index(): View
    {
        $positions = Position::latest()->get();

        return view('admin.positions.index', compact('positions'));
    }

    public function create(): View
    {
        return view('admin.positions.create');
    }

    public function store(PositionRequest $request): RedirectResponse
    {
        Position::create($request->validated());

        return redirect()->route('admin.positions.index')
            ->with('success', 'Position created successfully.');
    }

    public function edit(Position $position): View
    {
        return view('admin.positions.edit', compact('position'));
    }

    public function update(PositionRequest $request, Position $position): RedirectResponse
    {
        $position->update($request->validated());

        return redirect()->route('admin.positions.index')
            ->with('success', 'Position updated successfully.');
    }

    public function destroy(Position $position): RedirectResponse
    {
        if ($position->image) {
            Storage::disk('public')->delete('position-images/' . $position->image);
        }
        if ($position->company_logo) {
            Storage::disk('public')->delete('company-logos/' . $position->company_logo);
        }

        $position->delete();

        return redirect()->route('admin.positions.index')
            ->with('success', 'Position deleted successfully.');
    }

    public function uploadImage(Request $request, Position $position): RedirectResponse
    {
        $request->validate([
            'image' => ['required', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
        ]);

        if ($position->image) {
            Storage::disk('public')->delete('position-images/' . $position->image);
        }

        $file = $request->file('image');
        $filename = 'position_' . $position->id . '_' . time() . '.' . $file->getClientOriginalExtension();
        $file->storeAs('position-images', $filename, 'public');

        $position->update(['image' => $filename]);

        return back()->with('success', 'Position image uploaded successfully.');
    }

    public function deleteImage(Position $position): RedirectResponse
    {
        if (! $position->image) {
            return back()->with('error', 'No image to delete.');
        }

        Storage::disk('public')->delete('position-images/' . $position->image);
        $position->update(['image' => null]);

        return back()->with('success', 'Position image deleted successfully.');
    }

    public function uploadCompanyLogo(Request $request, Position $position): RedirectResponse
    {
        $request->validate([
            'company_logo' => ['required', 'image', 'mimes:jpeg,png,jpg,webp,svg', 'max:2048'],
        ]);

        if ($position->company_logo) {
            Storage::disk('public')->delete('company-logos/' . $position->company_logo);
        }

        $file = $request->file('company_logo');
        $filename = 'company_' . $position->id . '_' . time() . '.' . $file->getClientOriginalExtension();
        $file->storeAs('company-logos', $filename, 'public');

        $position->update(['company_logo' => $filename]);

        return back()->with('success', 'Company logo uploaded successfully.');
    }

    public function deleteCompanyLogo(Position $position): RedirectResponse
    {
        if (! $position->company_logo) {
            return back()->with('error', 'No company logo to delete.');
        }

        Storage::disk('public')->delete('company-logos/' . $position->company_logo);
        $position->update(['company_logo' => null]);

        return back()->with('success', 'Company logo deleted successfully.');
    }
}
