@extends('layouts.admin')

@section('title', 'Clients - Admin')

@section('content')
    <div class="min-h-screen">
        <div class="mb-8">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div class="min-w-0">
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight">Clients</h1>
                    <p class="mt-1 text-gray-500 text-sm sm:text-base">Client companies using the Seek & Recruit platform</p>
                </div>
                <a href="{{ route('admin.clients.create') }}">
                    <x-ui.button variant="primary">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                        Add Client
                    </x-ui.button>
                </a>
            </div>
        </div>

        @if ($clients->isEmpty())
            <x-ui.empty-state
                title="No clients yet"
                description="Add your first client company to start scoping positions and applications."
            >
                <x-slot:action>
                    <a href="{{ route('admin.clients.create') }}">
                        <x-ui.button variant="primary">Add Client</x-ui.button>
                    </a>
                </x-slot:action>
            </x-ui.empty-state>
        @else
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Client</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Industry</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Positions</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Applications</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Hires</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">HR Admins</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach ($clients as $client)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            @if ($client->logo_url)
                                                <img src="{{ $client->logo_url }}" alt="{{ $client->name }}" class="w-10 h-10 rounded-lg object-contain bg-gray-50 border border-gray-100" />
                                            @else
                                                <div class="w-10 h-10 rounded-lg bg-primary-100 text-primary-700 flex items-center justify-center text-sm font-semibold">
                                                    {{ strtoupper(mb_substr($client->name, 0, 2)) }}
                                                </div>
                                            @endif
                                            <div>
                                                <p class="font-medium text-gray-900">{{ $client->name }}</p>
                                                <p class="text-xs text-gray-500">{{ $client->slug }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-700">{{ $client->industry ?: '—' }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-900 font-medium">{{ $client->positions_count }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-900 font-medium">{{ $appsByClient[$client->id] ?? 0 }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-900 font-medium">{{ $hiresByClient[$client->id] ?? 0 }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-900 font-medium">{{ $client->users_count }}</td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium {{ $client->is_active ? 'bg-emerald-50 text-emerald-700 ring-1 ring-inset ring-emerald-600/20' : 'bg-gray-100 text-gray-600' }}">
                                            <span class="w-1.5 h-1.5 rounded-full {{ $client->is_active ? 'bg-emerald-500' : 'bg-gray-400' }}"></span>
                                            {{ $client->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('admin.clients.show', $client) }}" class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-100 rounded-lg">
                                                View
                                            </a>
                                            <a href="{{ route('admin.clients.edit', $client) }}" class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-100 rounded-lg">
                                                Edit
                                            </a>
                                        </div>
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
