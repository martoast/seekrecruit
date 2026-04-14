@extends('layouts.admin')

@section('title', ($candidate->user?->name ?? 'Candidate') . ' - Candidate Details')

@section('content')
    @php
        $user = $candidate->user;
        $name = $user?->name ?? 'Unknown';
        $initials = collect(explode(' ', $name))->take(2)->map(fn ($p) => mb_substr($p, 0, 1))->implode('') ?: '?';
        $genderLabels = [
            'male' => 'Male',
            'female' => 'Female',
            'other' => 'Other',
            'prefer_not_to_say' => 'Prefer not to say',
        ];
    @endphp

    <div class="min-h-screen">
        <div class="mb-6">
            <a href="{{ route('admin.candidates.index') }}" class="inline-flex items-center gap-2 text-sm font-medium text-gray-600 hover:text-gray-900">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                Back to Candidates
            </a>
        </div>

        <div class="space-y-6">
            {{-- Profile header --}}
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <div class="bg-linear-to-r from-primary-500 to-primary-600 h-24 sm:h-32"></div>
                <div class="px-4 sm:px-6 pb-6">
                    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between -mt-12 sm:-mt-16 gap-4">
                        <div class="flex flex-col sm:flex-row items-start sm:items-end gap-4">
                            @if ($candidate->profile_image_url)
                                <img src="{{ $candidate->profile_image_url }}" alt="{{ $name }}" class="w-24 h-24 sm:w-32 sm:h-32 rounded-2xl object-cover border-4 border-white shadow-lg" />
                            @else
                                <div class="w-24 h-24 sm:w-32 sm:h-32 rounded-2xl bg-primary-100 text-primary-700 flex items-center justify-center text-3xl sm:text-4xl font-bold border-4 border-white shadow-lg">
                                    {{ strtoupper($initials) }}
                                </div>
                            @endif
                            <div class="pb-1">
                                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">{{ $name }}</h1>
                                <p class="text-gray-500">{{ $user?->email }}</p>
                            </div>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            @if ($user?->email)
                                <a href="mailto:{{ $user->email }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-lg hover:bg-gray-50">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                                    Email
                                </a>
                            @endif
                            @if ($candidate->phone)
                                <a href="tel:{{ $candidate->phone }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-lg hover:bg-gray-50">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                                    Call
                                </a>
                            @endif
                            @if ($candidate->cv_path)
                                <a href="{{ route('admin.candidates.cv', $candidate) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-primary-500 rounded-lg hover:bg-primary-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                    Download CV
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 space-y-6">
                    {{-- Profile information --}}
                    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100">
                            <h2 class="text-lg font-semibold text-gray-900">Profile Information</h2>
                        </div>
                        <div class="p-6">
                            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 mb-1">University</dt>
                                    <dd class="text-sm text-gray-900">{{ $candidate->university ?: 'Not specified' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 mb-1">Degree</dt>
                                    <dd class="text-sm text-gray-900">{{ $candidate->degree ?: 'Not specified' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 mb-1">Semester</dt>
                                    <dd class="text-sm text-gray-900">{{ $candidate->semester ? 'Semester ' . $candidate->semester : 'Not specified' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 mb-1">Graduation Year</dt>
                                    <dd class="text-sm text-gray-900">{{ $candidate->graduation_year ?: 'Not specified' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 mb-1">Location</dt>
                                    <dd class="text-sm text-gray-900">{{ $candidate->location ?: 'Not specified' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 mb-1">Phone</dt>
                                    <dd class="text-sm text-gray-900">{{ $candidate->phone ?: 'Not specified' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 mb-1">Age</dt>
                                    <dd class="text-sm text-gray-900">{{ $candidate->age ? $candidate->age . ' years' : 'Not specified' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 mb-1">Gender</dt>
                                    <dd class="text-sm text-gray-900">{{ $candidate->gender ? ($genderLabels[$candidate->gender->value] ?? $candidate->gender->value) : 'Not specified' }}</dd>
                                </div>
                            </dl>

                            @if ($candidate->linkedin_url)
                                <div class="mt-6 pt-6 border-t border-gray-100">
                                    <dt class="text-sm font-medium text-gray-500 mb-1">LinkedIn</dt>
                                    <dd>
                                        <a href="{{ $candidate->linkedin_url }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 text-sm text-primary-600 hover:text-primary-700 font-medium">
                                            View LinkedIn Profile
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" /></svg>
                                        </a>
                                    </dd>
                                </div>
                            @endif

                            @if ($candidate->bio)
                                <div class="mt-6 pt-6 border-t border-gray-100">
                                    <dt class="text-sm font-medium text-gray-500 mb-2">Bio</dt>
                                    <dd class="text-sm text-gray-700 whitespace-pre-wrap">{{ $candidate->bio }}</dd>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Skills --}}
                    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100">
                            <h2 class="text-lg font-semibold text-gray-900">Skills</h2>
                        </div>
                        <div class="p-6">
                            @if (is_array($candidate->skills) && count($candidate->skills))
                                <div class="flex flex-wrap gap-2">
                                    @foreach ($candidate->skills as $skill)
                                        <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-medium bg-primary-50 text-primary-700 ring-1 ring-inset ring-primary-700/10">{{ $skill }}</span>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-sm text-gray-500">No skills listed yet</p>
                            @endif
                        </div>
                    </div>

                    {{-- Applications --}}
                    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100">
                            <h2 class="text-lg font-semibold text-gray-900">Applications</h2>
                        </div>
                        @if ($candidate->applications->count())
                            <div class="divide-y divide-gray-100">
                                @foreach ($candidate->applications as $app)
                                    <a href="{{ route('admin.applications.show', $app) }}" class="flex items-center justify-between p-4 hover:bg-gray-50 transition-colors group">
                                        <div class="flex items-center gap-4">
                                            <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                                            </div>
                                            <div>
                                                <p class="font-medium text-gray-900">{{ $app->position?->title }}</p>
                                                <p class="text-sm text-gray-500">Applied {{ $app->created_at->diffForHumans() }}</p>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-3">
                                            <x-ui.status-badge :status="$app->status" size="sm" />
                                            <svg class="w-4 h-4 text-gray-400 group-hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        @else
                            <div class="p-8 text-center">
                                <p class="text-sm text-gray-500">No applications yet</p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Sidebar --}}
                <div class="space-y-6">
                    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100">
                            <h3 class="text-lg font-semibold text-gray-900">Summary</h3>
                        </div>
                        <div class="p-6 space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Profile Photo</span>
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium {{ $candidate->profile_image_url ? 'bg-emerald-50 text-emerald-700 ring-1 ring-inset ring-emerald-600/20' : 'bg-gray-100 text-gray-600' }}">
                                    {{ $candidate->profile_image_url ? 'Uploaded' : 'None' }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">CV Status</span>
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium {{ $candidate->cv_path ? 'bg-emerald-50 text-emerald-700 ring-1 ring-inset ring-emerald-600/20' : 'bg-amber-50 text-amber-700 ring-1 ring-inset ring-amber-600/20' }}">
                                    {{ $candidate->cv_path ? 'Uploaded' : 'Missing' }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Applications</span>
                                <span class="text-sm font-semibold text-gray-900">{{ $candidate->applications->count() }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Skills</span>
                                <span class="text-sm font-semibold text-gray-900">{{ is_array($candidate->skills) ? count($candidate->skills) : 0 }}</span>
                            </div>
                            <div class="pt-4 border-t border-gray-100">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">Member Since</span>
                                    <span class="text-sm text-gray-900">{{ $candidate->created_at->format('M j, Y') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
