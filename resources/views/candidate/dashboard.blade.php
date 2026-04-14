@extends('layouts.candidate')

@section('title', 'Dashboard - Seek & Recruit Network')

@section('content')
    <div class="space-y-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">
                Welcome back, {{ $user->name }}!
            </h1>
            <p class="text-gray-600">Here's your application dashboard</p>
        </div>

        {{-- Profile completion card --}}
        @if ($profile)
            <x-ui.card padding="lg">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900 mb-1">Profile Completion</h2>
                        <p class="text-sm text-gray-600">Complete your profile to increase your chances</p>
                    </div>
                    <x-ui.badge :variant="$profileProgress === 100 ? 'success' : 'warning'">
                        {{ $profileProgress }}%
                    </x-ui.badge>
                </div>

                <div class="relative pt-1">
                    <div class="overflow-hidden h-3 text-xs flex rounded-full bg-gray-200">
                        <div
                            style="width: {{ $profileProgress }}%"
                            class="flex flex-col text-center whitespace-nowrap justify-center bg-primary-500 transition-all duration-500"
                        ></div>
                    </div>
                </div>

                @if ($profileProgress < 100)
                    <div class="mt-6 space-y-2">
                        <p class="text-sm font-medium text-gray-700">To complete your profile:</p>
                        <ul class="text-sm text-gray-600 space-y-1">
                            @unless ($profile->cv_path)
                                <li class="flex items-center">
                                    <svg class="h-4 w-4 mr-2 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd"/></svg>
                                    Upload your CV
                                </li>
                            @endunless
                            @unless ($profile->bio)
                                <li class="flex items-center">
                                    <svg class="h-4 w-4 mr-2 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd"/></svg>
                                    Add a bio
                                </li>
                            @endunless
                            @unless (is_array($profile->skills) && count($profile->skills))
                                <li class="flex items-center">
                                    <svg class="h-4 w-4 mr-2 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd"/></svg>
                                    Add your skills
                                </li>
                            @endunless
                        </ul>
                        <a href="{{ route('candidate.profile.edit') }}" class="inline-block mt-4">
                            <x-ui.button variant="primary" size="sm">Complete Profile</x-ui.button>
                        </a>
                    </div>
                @endif
            </x-ui.card>
        @endif

        {{-- Quick actions --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <a href="{{ route('candidate.profile.edit') }}">
                <x-ui.card padding="md" class="hover:border-primary-300 transition-all cursor-pointer h-full">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-primary-100 rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">My Profile</p>
                            <p class="text-lg font-semibold text-gray-900">View & Edit</p>
                        </div>
                    </div>
                </x-ui.card>
            </a>

            <a href="{{ route('positions.index') }}">
                <x-ui.card padding="md" class="hover:border-primary-300 transition-all cursor-pointer h-full">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Open Positions</p>
                            <p class="text-lg font-semibold text-gray-900">Browse Jobs</p>
                        </div>
                    </div>
                </x-ui.card>
            </a>

            <a href="{{ route('candidate.applications.index') }}">
                <x-ui.card padding="md" class="hover:border-primary-300 transition-all cursor-pointer h-full">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">My Applications</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $applications->count() }} Active</p>
                        </div>
                    </div>
                </x-ui.card>
            </a>
        </div>

        {{-- Recent applications --}}
        <div>
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gray-900">Recent Applications</h2>
                <a href="{{ route('candidate.applications.index') }}">
                    <x-ui.button variant="ghost" size="sm">View All →</x-ui.button>
                </a>
            </div>

            @if ($recentApplications->isEmpty())
                <x-ui.empty-state
                    title="No applications yet"
                    description="You haven't applied to any positions yet. Start exploring opportunities!"
                >
                    <x-slot:action>
                        <a href="{{ route('positions.index') }}">
                            <x-ui.button variant="primary">Browse Positions</x-ui.button>
                        </a>
                    </x-slot:action>
                </x-ui.empty-state>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($recentApplications as $application)
                        @include('candidate.applications._card', ['application' => $application])
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection
