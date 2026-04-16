<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ClientRequest;
use App\Models\Application;
use App\Models\Client;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ClientController extends Controller
{
    public function index(): View
    {
        $clients = Client::withCount(['positions', 'users'])
            ->orderBy('name')
            ->get();

        // Hires + applications totals per client (cheap-ish, we have at most a handful of clients)
        $hiresByClient = Application::query()
            ->selectRaw('positions.client_id, count(*) as hires')
            ->join('positions', 'applications.position_id', '=', 'positions.id')
            ->where('applications.status', 'hired')
            ->groupBy('positions.client_id')
            ->pluck('hires', 'positions.client_id');

        $appsByClient = Application::query()
            ->selectRaw('positions.client_id, count(*) as apps')
            ->join('positions', 'applications.position_id', '=', 'positions.id')
            ->groupBy('positions.client_id')
            ->pluck('apps', 'positions.client_id');

        return view('admin.clients.index', compact('clients', 'hiresByClient', 'appsByClient'));
    }

    public function create(): View
    {
        return view('admin.clients.create');
    }

    public function store(ClientRequest $request): RedirectResponse
    {
        Client::create($request->validated());

        return redirect()->route('admin.clients.index')
            ->with('success', 'Client created successfully.');
    }

    public function show(Client $client): View
    {
        $client->load(['users' => fn ($q) => $q->where('role', UserRole::HR_ADMIN->value)]);

        $positions = $client->positions()->latest()->get();

        $appsByStatus = Application::query()
            ->whereHas('position', fn ($q) => $q->where('client_id', $client->id))
            ->selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $recentHires = Application::with(['candidate.user', 'position'])
            ->whereHas('position', fn ($q) => $q->where('client_id', $client->id))
            ->where('status', 'hired')
            ->latest('updated_at')
            ->take(5)
            ->get();

        return view('admin.clients.show', compact('client', 'positions', 'appsByStatus', 'recentHires'));
    }

    public function edit(Client $client): View
    {
        return view('admin.clients.edit', compact('client'));
    }

    public function update(ClientRequest $request, Client $client): RedirectResponse
    {
        $client->update($request->validated());

        return redirect()->route('admin.clients.index')
            ->with('success', 'Client updated successfully.');
    }

    public function destroy(Client $client): RedirectResponse
    {
        // Unassign HR admins bound to this client before soft-deleting; they'll
        // become orphaned HR admins that a Super Admin can reassign later.
        User::where('client_id', $client->id)->update(['client_id' => null]);

        if ($client->logo) {
            Storage::disk('public')->delete('client-logos/' . $client->logo);
        }

        $client->delete();

        return redirect()->route('admin.clients.index')
            ->with('success', 'Client archived. Positions hidden, applications preserved.');
    }

    public function uploadLogo(Request $request, Client $client): RedirectResponse
    {
        $request->validate([
            'logo' => ['required', 'image', 'mimes:jpeg,png,jpg,webp,svg', 'max:2048'],
        ]);

        if ($client->logo) {
            Storage::disk('public')->delete('client-logos/' . $client->logo);
        }

        $file = $request->file('logo');
        $filename = 'client_' . $client->id . '_' . time() . '.' . $file->getClientOriginalExtension();
        $file->storeAs('client-logos', $filename, 'public');

        $client->update(['logo' => $filename]);

        return back()->with('success', 'Client logo uploaded successfully.');
    }

    public function deleteLogo(Client $client): RedirectResponse
    {
        if (! $client->logo) {
            return back()->with('error', 'No logo to delete.');
        }

        Storage::disk('public')->delete('client-logos/' . $client->logo);
        $client->update(['logo' => null]);

        return back()->with('success', 'Client logo deleted successfully.');
    }
}
