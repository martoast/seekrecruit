<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreAdminUserRequest;
use App\Http\Requests\Admin\UpdateAdminUserRequest;
use App\Models\Client;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(): View
    {
        // List everyone with admin-tier access. Super admins are read-only from the UI;
        // HR admins can be created, edited, and deleted.
        $admins = User::with('client')
            ->whereIn('role', [UserRole::HR_ADMIN->value, UserRole::SUPER_ADMIN->value])
            ->orderBy('role')
            ->orderBy('name')
            ->get();

        return view('admin.admins.index', compact('admins'));
    }

    public function create(): View
    {
        $clients = Client::where('is_active', true)->orderBy('name')->get();

        return view('admin.admins.create', compact('clients'));
    }

    public function store(StoreAdminUserRequest $request): RedirectResponse
    {
        User::create([
            'name' => $request->string('name'),
            'email' => $request->string('email'),
            'password' => Hash::make($request->string('password')),
            'role' => UserRole::HR_ADMIN,
            'client_id' => $request->integer('client_id'),
            'email_verified_at' => now(),
        ]);

        return redirect()->route('admin.admins.index')
            ->with('success', 'HR Admin created successfully.');
    }

    public function edit(User $user): View
    {
        abort_if($user->isSuperAdmin(), 403, 'Super admins can only be managed via the database.');

        $clients = Client::orderBy('name')->get();

        return view('admin.admins.edit', compact('user', 'clients'));
    }

    public function update(UpdateAdminUserRequest $request, User $user): RedirectResponse
    {
        abort_if($user->isSuperAdmin(), 403);

        $data = [
            'name' => $request->string('name'),
            'email' => $request->string('email'),
            'client_id' => $request->integer('client_id'),
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->string('password'));
        }

        $user->update($data);

        return redirect()->route('admin.admins.index')
            ->with('success', 'HR Admin updated successfully.');
    }

    public function destroy(User $user): RedirectResponse
    {
        abort_if($user->isSuperAdmin(), 403, 'Super admins cannot be deleted via the UI.');
        abort_if($user->id === auth()->id(), 403, 'You cannot delete yourself.');

        $user->delete();

        return redirect()->route('admin.admins.index')
            ->with('success', 'HR Admin deleted.');
    }
}
