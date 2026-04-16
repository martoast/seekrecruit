@extends('layouts.admin')

@section('title', $client->name . ' - Client')

@section('content')
    @php
        $totalApps = array_sum($appsByStatus);
        $hired = $appsByStatus['hired'] ?? 0;
        $inPipeline = $totalApps - $hired - ($appsByStatus['discarded'] ?? 0);
    @endphp

    <div class="min-h-screen">
        <div class="mb-6">
            <a href="{{ route('admin.clients.index') }}" class="inline-flex items-center gap-2 text-sm font-medium text-gray-600 hover:text-gray-900">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                Back to Clients
            </a>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden mb-6">
            <div class="p-6">
                <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                    <div class="flex items-start gap-4">
                        @if ($client->logo_url)
                            <img src="{{ $client->logo_url }}" alt="{{ $client->name }}" class="w-16 h-16 rounded-xl object-contain bg-white border border-gray-200 p-1" />
                        @else
                            <div class="w-16 h-16 rounded-xl bg-primary-100 text-primary-700 flex items-center justify-center text-xl font-bold">
                                {{ strtoupper(mb_substr($client->name, 0, 2)) }}
                            </div>
                        @endif
                        <div>
                            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">{{ $client->name }}</h1>
                            <p class="text-gray-500">{{ $client->industry ?: 'No industry set' }} · {{ $client->slug }}</p>
                            <x-ui.badge :variant="$client->is_active ? 'success' : 'default'" class="mt-2">
                                {{ $client->is_active ? 'Active' : 'Inactive' }}
                            </x-ui.badge>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('admin.clients.edit', $client) }}">
                            <x-ui.button variant="secondary">Edit</x-ui.button>
                        </a>
                        <a href="{{ route('admin.dashboard', ['client_id' => $client->id]) }}">
                            <x-ui.button variant="primary">View Dashboard</x-ui.button>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                {{-- KPIs --}}
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    <div class="bg-white rounded-xl border border-gray-200 p-4">
                        <p class="text-xs text-gray-500 uppercase tracking-wide">Positions</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ $positions->count() }}</p>
                    </div>
                    <div class="bg-white rounded-xl border border-gray-200 p-4">
                        <p class="text-xs text-gray-500 uppercase tracking-wide">Applications</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ $totalApps }}</p>
                    </div>
                    <div class="bg-white rounded-xl border border-gray-200 p-4">
                        <p class="text-xs text-gray-500 uppercase tracking-wide">In Pipeline</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ $inPipeline }}</p>
                    </div>
                    <div class="bg-white rounded-xl border border-gray-200 p-4">
                        <p class="text-xs text-gray-500 uppercase tracking-wide">Hired</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ $hired }}</p>
                    </div>
                </div>

                {{-- Positions --}}
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-gray-900">Positions</h2>
                        <a href="{{ route('admin.positions.index', ['client_id' => $client->id]) }}" class="text-sm font-medium text-primary-600 hover:text-primary-700">
                            Manage all →
                        </a>
                    </div>
                    @if ($positions->isEmpty())
                        <div class="p-8 text-center text-sm text-gray-500">No positions for this client yet.</div>
                    @else
                        <div class="divide-y divide-gray-100">
                            @foreach ($positions as $position)
                                <a href="{{ route('admin.positions.edit', $position) }}" class="flex items-center justify-between p-4 hover:bg-gray-50 group">
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $position->title }}</p>
                                        <p class="text-sm text-gray-500">{{ $position->location }}</p>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <x-ui.badge :variant="$position->status?->value === 'open' ? 'success' : ($position->status?->value === 'draft' ? 'warning' : 'default')" size="sm">
                                            {{ ucfirst($position->status?->value ?? 'Unknown') }}
                                        </x-ui.badge>
                                        <svg class="w-4 h-4 text-gray-400 group-hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- Recent hires --}}
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h2 class="text-lg font-semibold text-gray-900">Recent Hires</h2>
                    </div>
                    @if ($recentHires->isEmpty())
                        <div class="p-8 text-center text-sm text-gray-500">No hires yet.</div>
                    @else
                        <div class="divide-y divide-gray-100">
                            @foreach ($recentHires as $hire)
                                <div class="flex items-center justify-between p-4">
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $hire->candidate?->user?->name }}</p>
                                        <p class="text-sm text-gray-500">{{ $hire->position?->title }}</p>
                                    </div>
                                    <p class="text-sm text-gray-500">{{ $hire->updated_at->diffForHumans() }}</p>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-900">HR Admins</h3>
                    </div>
                    @if ($client->users->isEmpty())
                        <div class="p-6 text-center text-sm text-gray-500">
                            No admin assigned to this client yet.
                            <div class="mt-3">
                                <a href="{{ route('admin.admins.create') }}">
                                    <x-ui.button variant="primary" size="sm">Add HR Admin</x-ui.button>
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="divide-y divide-gray-100">
                            @foreach ($client->users as $admin)
                                <div class="p-4">
                                    <p class="font-medium text-gray-900">{{ $admin->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $admin->email }}</p>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-900">Pipeline</h3>
                    </div>
                    <div class="p-6 space-y-3">
                        @foreach (['registered', 'preselected', 'interview', 'evaluation', 'finalist', 'hired', 'discarded'] as $status)
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-700 capitalize">{{ str_replace('_', ' ', $status) }}</span>
                                <span class="font-semibold text-gray-900">{{ $appsByStatus[$status] ?? 0 }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
