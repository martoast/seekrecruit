@extends('layouts.app')

@section('title', $position->title . ' - Seek & Recruit Network')

@section('content')
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-6">
        @if ($position->image_url)
            <div class="relative h-64 sm:h-80 rounded-2xl overflow-hidden">
                <img src="{{ $position->image_url }}" alt="{{ $position->title }}" class="w-full h-full object-cover" />
                <div class="absolute inset-0 bg-linear-to-t from-black/60 via-transparent to-transparent"></div>
                <div class="absolute bottom-0 left-0 right-0 p-6">
                    @if ($position->is_active)
                        <x-ui.badge variant="success" class="mb-3">Open Position</x-ui.badge>
                    @endif
                    <h1 class="text-2xl sm:text-3xl font-bold text-white mb-2">{{ $position->title }}</h1>
                    <div class="flex flex-wrap items-center gap-4 text-white/90">
                        <div class="flex items-center gap-1.5">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            {{ $position->location }}
                        </div>
                        <div class="flex items-center gap-1.5">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            Posted {{ $position->created_at->format('F j, Y') }}
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <x-ui.card padding="lg">
            <div class="space-y-6">
                @if (!$position->image_url)
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex-1">
                            <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $position->title }}</h1>
                            <div class="flex flex-wrap items-center gap-4 text-gray-600">
                                <div class="flex items-center">
                                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    {{ $position->location }}
                                </div>
                                <div class="flex items-center">
                                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    Posted {{ $position->created_at->format('F j, Y') }}
                                </div>
                            </div>
                        </div>
                        @if ($position->is_active)
                            <x-ui.badge variant="success">Open</x-ui.badge>
                        @endif
                    </div>
                @endif

                @if ($position->company_name || $position->company_logo_url)
                    <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-xl">
                        @if ($position->company_logo_url)
                            <img src="{{ $position->company_logo_url }}" alt="{{ $position->company_name ?? 'Company' }}" class="w-14 h-14 rounded-xl object-contain bg-white border border-gray-200 p-1" />
                        @else
                            <div class="w-14 h-14 rounded-xl bg-white border border-gray-200 flex items-center justify-center">
                                <svg class="w-7 h-7 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </div>
                        @endif
                        <div>
                            <p class="text-sm text-gray-500">Posted by</p>
                            <p class="font-semibold text-gray-900">{{ $position->company_name ?? 'Company' }}</p>
                        </div>
                    </div>
                @endif

                @auth
                    @if (auth()->user()->isCandidate() && $position->is_active)
                        <div class="flex flex-wrap items-center gap-3">
                            @if ($hasApplied)
                                <div class="flex items-center gap-2 px-4 py-2 bg-green-50 border border-green-200 rounded-lg">
                                    <svg class="h-5 w-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span class="text-green-700 font-medium">Already Applied</span>
                                </div>
                                <a href="{{ route('candidate.applications.index') }}">
                                    <x-ui.button variant="primary">View My Applications</x-ui.button>
                                </a>
                            @else
                                <form method="POST" action="{{ route('candidate.applications.store') }}">
                                    @csrf
                                    <input type="hidden" name="position_id" value="{{ $position->id }}" />
                                    <x-ui.button type="submit" variant="primary">Apply Now</x-ui.button>
                                </form>
                            @endif
                            <a href="{{ route('positions.index') }}">
                                <x-ui.button variant="secondary">Back to Positions</x-ui.button>
                            </a>
                        </div>
                    @endif
                @endauth

                <div class="border-t border-gray-200 pt-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Description</h2>
                    <div class="text-gray-700 whitespace-pre-line">{{ $position->description }}</div>
                </div>

                <div class="border-t border-gray-200 pt-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Requirements</h2>
                    <div class="text-gray-700 whitespace-pre-line">{{ $position->requirements }}</div>
                </div>
            </div>
        </x-ui.card>

        @guest
            <div class="text-center py-8">
                <p class="text-gray-600 mb-4">Want to apply for this position?</p>
                <div class="flex gap-3 justify-center">
                    <a href="{{ route('login') }}"><x-ui.button variant="secondary">Login</x-ui.button></a>
                    <a href="{{ route('register') }}"><x-ui.button variant="primary">Register</x-ui.button></a>
                </div>
            </div>
        @endguest
    </div>
@endsection
