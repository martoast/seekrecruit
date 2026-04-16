@extends('layouts.admin')

@section('title', 'Admins - Admin')

@section('content')
    <div class="min-h-screen">
        <div class="mb-8">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div class="min-w-0">
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight">Admins</h1>
                    <p class="mt-1 text-gray-500 text-sm sm:text-base">HR admins scoped to a client + Super admins with platform access</p>
                </div>
                <a href="{{ route('admin.admins.create') }}">
                    <x-ui.button variant="primary">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                        Add HR Admin
                    </x-ui.button>
                </a>
            </div>
        </div>

        @if ($admins->isEmpty())
            <x-ui.empty-state
                title="No admins yet"
                description="Add your first HR admin and assign them to a client."
            >
                <x-slot:action>
                    <a href="{{ route('admin.admins.create') }}">
                        <x-ui.button variant="primary">Add HR Admin</x-ui.button>
                    </a>
                </x-slot:action>
            </x-ui.empty-state>
        @else
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Name</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Email</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Role</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Client</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Created</th>
                                <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach ($admins as $admin)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <p class="font-medium text-gray-900">{{ $admin->name }}</p>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-700">{{ $admin->email }}</td>
                                    <td class="px-6 py-4">
                                        <x-ui.badge :variant="$admin->isSuperAdmin() ? 'info' : 'default'" size="sm">
                                            {{ $admin->role->label() }}
                                        </x-ui.badge>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-700">
                                        {{ $admin->client?->name ?? '—' }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        {{ $admin->created_at->format('M j, Y') }}
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        @if ($admin->isSuperAdmin())
                                            <span class="text-xs text-gray-400">platform-owner</span>
                                        @else
                                            <div class="flex items-center justify-end gap-1">
                                                <a href="{{ route('admin.admins.edit', $admin) }}" class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-100 rounded-lg">
                                                    Edit
                                                </a>
                                                @if ($admin->id !== auth()->id())
                                                    <form method="POST" action="{{ route('admin.admins.destroy', $admin) }}" onsubmit="return confirm('Delete this HR admin? This can\'t be undone.');" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="p-2 text-gray-500 hover:text-red-600 hover:bg-red-50 rounded-lg">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
@endsection
