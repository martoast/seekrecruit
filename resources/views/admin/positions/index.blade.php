@extends('layouts.admin')

@section('title', 'Positions - Admin')

@section('content')
    @php
        $activeCount = $positions->where('is_active', true)->count();
        $inactiveCount = $positions->where('is_active', false)->count();
    @endphp

    <div class="min-h-screen">
        <div class="mb-8">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div class="min-w-0">
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight">Positions</h1>
                    <p class="mt-1 text-gray-500 text-sm sm:text-base">Manage job positions and openings</p>
                </div>
                <a href="{{ route('admin.positions.create') }}">
                    <x-ui.button variant="primary">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                        Create Position
                    </x-ui.button>
                </a>
            </div>

            <div class="grid grid-cols-3 gap-4 mt-6">
                <div class="bg-white rounded-xl border border-gray-200 p-4">
                    <p class="text-xs text-gray-500 uppercase tracking-wide">Total</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $positions->count() }}</p>
                </div>
                <div class="bg-white rounded-xl border border-gray-200 p-4">
                    <p class="text-xs text-gray-500 uppercase tracking-wide">Active</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $activeCount }}</p>
                </div>
                <div class="bg-white rounded-xl border border-gray-200 p-4">
                    <p class="text-xs text-gray-500 uppercase tracking-wide">Inactive</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $inactiveCount }}</p>
                </div>
            </div>
        </div>

        @if ($positions->isEmpty())
            <div class="bg-white rounded-xl border border-gray-200 p-12">
                <div class="text-center max-w-md mx-auto">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">No positions yet</h3>
                    <p class="text-gray-500 mb-6">Create your first job position to start receiving applications.</p>
                    <a href="{{ route('admin.positions.create') }}">
                        <x-ui.button variant="primary">Create Position</x-ui.button>
                    </a>
                </div>
            </div>
        @else
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Image</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Position</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Location</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Created</th>
                                <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach ($positions as $position)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4">
                                        @if ($position->image_url)
                                            <div class="w-16 h-12 rounded-lg overflow-hidden bg-gray-100">
                                                <img src="{{ $position->image_url }}" alt="{{ $position->title }}" class="w-full h-full object-cover" />
                                            </div>
                                        @else
                                            <div class="w-16 h-12 rounded-lg bg-linear-to-br from-primary-500 to-primary-600 flex items-center justify-center">
                                                <svg class="w-6 h-6 text-white/50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            @if ($position->company_logo_url)
                                                <img src="{{ $position->company_logo_url }}" alt="{{ $position->company_name ?? 'Company' }}" class="w-8 h-8 rounded-lg object-contain bg-gray-50 border border-gray-100" />
                                            @endif
                                            <div>
                                                <p class="font-medium text-gray-900">{{ $position->title }}</p>
                                                @if ($position->company_name)
                                                    <p class="text-xs text-gray-500">{{ $position->company_name }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-sm text-gray-700">{{ $position->location }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium {{ $position->is_active ? 'bg-emerald-50 text-emerald-700 ring-1 ring-inset ring-emerald-600/20' : 'bg-gray-100 text-gray-600' }}">
                                            <span class="w-1.5 h-1.5 rounded-full {{ $position->is_active ? 'bg-emerald-500' : 'bg-gray-400' }}"></span>
                                            {{ $position->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-sm text-gray-600">{{ $position->created_at->format('M j, Y') }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route('admin.positions.edit', $position) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-100 rounded-lg">
                                            Edit
                                        </a>
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
