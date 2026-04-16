@extends('layouts.admin')

@section('title', 'Admin Dashboard - Seek & Recruit Network')

@section('content')
    @php
        $hour = now()->hour;
        $greeting = $hour < 12 ? 'Good morning' : ($hour < 18 ? 'Good afternoon' : 'Good evening');
        $statusesByKey = $stats['applications_by_status'] ?? [];
        $totalApps = array_sum($statusesByKey);

        $statusMeta = [
            'registered' => ['label' => 'Registered', 'bar' => 'bg-blue-500'],
            'preselected' => ['label' => 'Pre-selected', 'bar' => 'bg-cyan-500'],
            'interview' => ['label' => 'Interview', 'bar' => 'bg-purple-500'],
            'evaluation' => ['label' => 'Evaluation', 'bar' => 'bg-amber-500'],
            'finalist' => ['label' => 'Finalist', 'bar' => 'bg-indigo-500'],
            'hired' => ['label' => 'Hired', 'bar' => 'bg-emerald-500'],
            'discarded' => ['label' => 'Discarded', 'bar' => 'bg-gray-400'],
        ];
    @endphp

    <div class="min-h-screen">
        @if (!empty($activeClient))
            <div class="mb-6 flex items-center justify-between gap-3 p-4 bg-primary-50 border border-primary-100 rounded-xl">
                <div class="flex items-center gap-3">
                    @if ($activeClient->logo_url)
                        <img src="{{ $activeClient->logo_url }}" alt="{{ $activeClient->name }}" class="w-10 h-10 rounded-lg object-contain bg-white border border-gray-100 p-1" />
                    @else
                        <div class="w-10 h-10 rounded-lg bg-primary-100 text-primary-700 flex items-center justify-center text-sm font-semibold">
                            {{ strtoupper(mb_substr($activeClient->name, 0, 2)) }}
                        </div>
                    @endif
                    <div>
                        <p class="text-xs text-primary-700 uppercase tracking-wide font-medium">Viewing</p>
                        <p class="font-semibold text-gray-900">{{ $activeClient->name }}</p>
                    </div>
                </div>
                @if (auth()->user()->isSuperAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="text-sm font-medium text-primary-700 hover:text-primary-800">
                        Show all clients →
                    </a>
                @endif
            </div>
        @endif

        <div class="mb-8">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div class="min-w-0">
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight">Dashboard</h1>
                    <p class="mt-1 text-gray-500 text-sm sm:text-base">{{ $greeting }}, here's your recruitment overview</p>
                </div>
                <a href="{{ route('admin.positions.create') }}">
                    <x-ui.button variant="primary">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                        New Position
                    </x-ui.button>
                </a>
            </div>
        </div>

        <div class="space-y-6">
            {{-- Key metrics --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white rounded-xl border border-gray-200 p-5 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        </div>
                        <a href="{{ route('admin.candidates.index') }}" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                        </a>
                    </div>
                    <div class="mt-4">
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['total_candidates'] }}</p>
                        <p class="text-sm text-gray-500 mt-1">Total Candidates</p>
                    </div>
                </div>

                <div class="bg-white rounded-xl border border-gray-200 p-5 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center">
                            <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <a href="{{ route('admin.applications.index') }}" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                        </a>
                    </div>
                    <div class="mt-4">
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['total_applications'] }}</p>
                        <p class="text-sm text-gray-500 mt-1">Total Applications</p>
                    </div>
                </div>

                <div class="bg-white rounded-xl border border-gray-200 p-5 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div class="w-12 h-12 rounded-xl bg-violet-50 flex items-center justify-center">
                            <svg class="w-6 h-6 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        <a href="{{ route('admin.interviews.index') }}" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                        </a>
                    </div>
                    <div class="mt-4">
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['interviews_this_week'] }}</p>
                        <p class="text-sm text-gray-500 mt-1">Interviews This Week</p>
                    </div>
                </div>

                <div class="bg-white rounded-xl border border-gray-200 p-5 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div class="w-12 h-12 rounded-xl bg-amber-50 flex items-center justify-center">
                            <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                    </div>
                    <div class="mt-4">
                        <p class="text-3xl font-bold text-gray-900">{{ $statusesByKey['hired'] ?? 0 }}</p>
                        <p class="text-sm text-gray-500 mt-1">Hired Candidates</p>
                    </div>
                </div>
            </div>

            {{-- Pipeline + Top universities --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 bg-white rounded-xl border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h2 class="text-lg font-semibold text-gray-900">Application Pipeline</h2>
                        <p class="text-sm text-gray-500 mt-0.5">Current status distribution</p>
                    </div>
                    <div class="p-6 space-y-4">
                        @foreach ($statusesByKey as $key => $count)
                            @php
                                $meta = $statusMeta[$key] ?? ['label' => ucfirst($key), 'bar' => 'bg-gray-400'];
                                $percent = $totalApps > 0 ? round(($count / $totalApps) * 100) : 0;
                            @endphp
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm font-medium text-gray-700">{{ $meta['label'] }}</span>
                                    <span class="text-sm font-semibold text-gray-900">{{ $count }}</span>
                                </div>
                                <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                                    <div class="h-full rounded-full {{ $meta['bar'] }} transition-all duration-500" style="width: {{ $percent }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h2 class="text-lg font-semibold text-gray-900">Top Universities</h2>
                        <p class="text-sm text-gray-500 mt-0.5">Most common institutions</p>
                    </div>
                    <div class="p-6">
                        @if (count($stats['top_universities']))
                            <div class="space-y-4">
                                @foreach ($stats['top_universities'] as $index => $uni)
                                    @php
                                        $badgeClasses = match ($index) {
                                            0 => 'bg-amber-100 text-amber-700',
                                            1 => 'bg-gray-200 text-gray-700',
                                            2 => 'bg-orange-100 text-orange-700',
                                            default => 'bg-gray-100 text-gray-500',
                                        };
                                    @endphp
                                    <div class="flex items-center gap-3">
                                        <span class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold {{ $badgeClasses }}">
                                            {{ $index + 1 }}
                                        </span>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900 truncate">{{ $uni['name'] }}</p>
                                        </div>
                                        <span class="text-sm font-semibold text-gray-700">{{ $uni['count'] }}</span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-sm text-gray-500 text-center py-8">No university data yet</p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Recent applications --}}
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">Recent Applications</h2>
                        <p class="text-sm text-gray-500 mt-0.5">Latest candidate submissions</p>
                    </div>
                    <a href="{{ route('admin.applications.index') }}" class="text-sm font-medium text-primary-600 hover:text-primary-700 inline-flex items-center gap-1">
                        View all
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                    </a>
                </div>

                @if (count($stats['recent_applications']))
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Candidate</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Position</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Applied</th>
                                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach ($stats['recent_applications'] as $app)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4">
                                            <p class="font-medium text-gray-900">{{ $app->candidate?->user?->name ?? '—' }}</p>
                                            <p class="text-sm text-gray-500">{{ $app->candidate?->user?->email }}</p>
                                        </td>
                                        <td class="px-6 py-4">
                                            <p class="text-sm font-medium text-gray-900">{{ $app->position?->title }}</p>
                                            <p class="text-sm text-gray-500">{{ $app->position?->location }}</p>
                                        </td>
                                        <td class="px-6 py-4">
                                            <x-ui.status-badge :status="$app->status" size="sm" />
                                        </td>
                                        <td class="px-6 py-4">
                                            <p class="text-sm text-gray-900">{{ $app->created_at->format('M j, Y') }}</p>
                                            <p class="text-sm text-gray-500">{{ $app->created_at->diffForHumans() }}</p>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <a href="{{ route('admin.applications.show', $app) }}" class="inline-flex items-center gap-1 px-3 py-1.5 text-sm font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-100 rounded-lg">
                                                Review
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="p-12 text-center">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">No applications yet</h3>
                        <p class="text-gray-500">Applications will appear here when candidates apply.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
