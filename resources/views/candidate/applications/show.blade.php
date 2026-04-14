@extends('layouts.candidate')

@section('title', ($application->position?->title ?? 'Application') . ' - My Applications')

@section('content')
    @php
        $status = $application->status->value;
        $progressMap = [
            'registered' => 1, 'preselected' => 2, 'interview' => 3,
            'evaluation' => 4, 'finalist' => 5, 'hired' => 6, 'discarded' => 0,
        ];
        $step = $progressMap[$status] ?? 0;
        $progress = $step === 0 ? 0 : (int) round($step / 6 * 100);
        $barClass = match ($status) {
            'discarded' => 'bg-gray-400',
            'hired' => 'bg-emerald-500',
            default => 'bg-primary-500',
        };
    @endphp
    <div class="max-w-4xl mx-auto space-y-6">
        <div>
            <a href="{{ route('candidate.applications.index') }}" class="text-sm text-primary-600 hover:text-primary-700 mb-2 inline-block">
                ← Back to Applications
            </a>
            <h1 class="text-3xl font-bold text-gray-900">{{ $application->position?->title }}</h1>
            <p class="text-gray-600 mt-1">{{ $application->position?->location }}</p>
        </div>

        {{-- Status --}}
        <x-ui.card padding="lg">
            <div class="space-y-6">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-semibold text-gray-900">Application Status</h2>
                    <x-ui.status-badge :status="$application->status" />
                </div>

                <div class="relative">
                    <div class="overflow-hidden h-3 text-xs flex rounded-full bg-gray-200">
                        <div style="width: {{ $progress }}%" class="{{ $barClass }} transition-all duration-500"></div>
                    </div>
                    <div class="flex justify-between mt-2 text-xs text-gray-600">
                        <span>Registered</span>
                        <span>Pre-selected</span>
                        <span>Interview</span>
                        <span>Evaluation</span>
                        <span>Finalist</span>
                        <span>Hired</span>
                    </div>
                </div>

                <div class="flex items-center text-sm text-gray-600">
                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Applied {{ $application->created_at->format('F j, Y') }}
                </div>
            </div>
        </x-ui.card>

        {{-- Position details --}}
        <x-ui.card padding="lg">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Position Details</h2>
            <div class="space-y-4">
                <div>
                    <h3 class="text-sm font-medium text-gray-700 mb-2">Description</h3>
                    <p class="text-gray-600 whitespace-pre-line">{{ $application->position?->description }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-700 mb-2">Requirements</h3>
                    <p class="text-gray-600 whitespace-pre-line">{{ $application->position?->requirements }}</p>
                </div>
            </div>
        </x-ui.card>

        {{-- Interviews --}}
        @if ($application->interviews && $application->interviews->count())
            <div>
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Scheduled Interviews</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach ($application->interviews as $interview)
                        @php
                            $upcoming = $interview->scheduled_at->isFuture();
                            $typeLabel = match ($interview->type->value) {
                                'technical' => 'Technical',
                                'hr' => 'HR Interview',
                                'final' => 'Final Interview',
                                default => ucfirst($interview->type->value),
                            };
                        @endphp
                        <x-ui.card padding="md">
                            <div class="space-y-3">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <h4 class="text-base font-semibold text-gray-900 mb-1">{{ $typeLabel }}</h4>
                                        @if ($interview->location)
                                            <p class="text-sm text-gray-600">{{ $interview->location }}</p>
                                        @endif
                                    </div>
                                    <x-ui.badge :variant="$upcoming ? 'info' : 'default'" size="sm">
                                        {{ $upcoming ? 'Upcoming' : 'Past' }}
                                    </x-ui.badge>
                                </div>

                                <div class="flex items-center gap-4 text-sm text-gray-600">
                                    <div class="flex items-center">
                                        <svg class="h-4 w-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        {{ $interview->scheduled_at->format('F j, Y') }}
                                    </div>
                                    <div class="flex items-center">
                                        <svg class="h-4 w-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        {{ $interview->scheduled_at->format('g:i A') }}
                                    </div>
                                </div>

                                @if ($interview->notes)
                                    <div class="pt-3 border-t border-gray-100">
                                        <p class="text-sm text-gray-700">{{ $interview->notes }}</p>
                                    </div>
                                @endif
                            </div>
                        </x-ui.card>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
@endsection
