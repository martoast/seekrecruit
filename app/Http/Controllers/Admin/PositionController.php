<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\ScopesToClient;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PositionRequest;
use App\Models\Client;
use App\Models\Position;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PositionController extends Controller
{
    use ScopesToClient;

    public function index(Request $request): View
    {
        $user = $request->user();
        $clientId = $this->activeClientId($user, $request->integer('client_id') ?: null);

        $query = Position::with('client')->latest();

        if ($clientId) {
            $query->where('client_id', $clientId);
        }

        $positions = $query->get();

        $clients = $user->isSuperAdmin() ? Client::orderBy('name')->get() : collect();
        $activeClient = $this->activeClient($user, $request->integer('client_id') ?: null);

        return view('admin.positions.index', compact('positions', 'clients', 'activeClient'));
    }

    public function create(Request $request): View
    {
        $user = $request->user();
        $clients = $user->isSuperAdmin() ? Client::where('is_active', true)->orderBy('name')->get() : collect();
        $defaultClientId = $user->isHrAdmin() ? $user->client_id : null;

        return view('admin.positions.create', compact('clients', 'defaultClientId'));
    }

    public function store(PositionRequest $request): RedirectResponse
    {
        $user = $request->user();
        $data = $request->validated();

        // HR admins may never create positions outside their own client —
        // force it regardless of what was submitted.
        if ($user->isHrAdmin()) {
            $data['client_id'] = $user->client_id;
        }

        abort_unless($this->userOwnsClient($user, (int) $data['client_id']), 403);

        Position::create($data);

        return redirect()->route('admin.positions.index')
            ->with('success', 'Position created successfully.');
    }

    public function edit(Request $request, Position $position): View
    {
        $user = $request->user();
        abort_unless($this->userOwnsClient($user, $position->client_id), 403);

        $clients = $user->isSuperAdmin() ? Client::orderBy('name')->get() : collect();

        return view('admin.positions.edit', compact('position', 'clients'));
    }

    public function update(PositionRequest $request, Position $position): RedirectResponse
    {
        $user = $request->user();
        abort_unless($this->userOwnsClient($user, $position->client_id), 403);

        $data = $request->validated();

        // HR admins can't reassign positions to a different client.
        if ($user->isHrAdmin()) {
            $data['client_id'] = $user->client_id;
        }

        abort_unless($this->userOwnsClient($user, (int) $data['client_id']), 403);

        $position->update($data);

        return redirect()->route('admin.positions.index')
            ->with('success', 'Position updated successfully.');
    }

    public function destroy(Request $request, Position $position): RedirectResponse
    {
        abort_unless($this->userOwnsClient($request->user(), $position->client_id), 403);

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
        abort_unless($this->userOwnsClient($request->user(), $position->client_id), 403);

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

    public function deleteImage(Request $request, Position $position): RedirectResponse
    {
        abort_unless($this->userOwnsClient($request->user(), $position->client_id), 403);

        if (! $position->image) {
            return back()->with('error', 'No image to delete.');
        }

        Storage::disk('public')->delete('position-images/' . $position->image);
        $position->update(['image' => null]);

        return back()->with('success', 'Position image deleted successfully.');
    }

    public function uploadCompanyLogo(Request $request, Position $position): RedirectResponse
    {
        abort_unless($this->userOwnsClient($request->user(), $position->client_id), 403);

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

    public function deleteCompanyLogo(Request $request, Position $position): RedirectResponse
    {
        abort_unless($this->userOwnsClient($request->user(), $position->client_id), 403);

        if (! $position->company_logo) {
            return back()->with('error', 'No company logo to delete.');
        }

        Storage::disk('public')->delete('company-logos/' . $position->company_logo);
        $position->update(['company_logo' => null]);

        return back()->with('success', 'Company logo deleted successfully.');
    }
}
