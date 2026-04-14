@php
    $status = $application->status->value;
    $progressMap = [
        'registered' => 1,
        'preselected' => 2,
        'interview' => 3,
        'evaluation' => 4,
        'finalist' => 5,
        'hired' => 6,
        'discarded' => 0,
    ];
    $step = $progressMap[$status] ?? 0;
    $progress = $step === 0 ? 0 : (int) round($step / 6 * 100);
    $barClass = match ($status) {
        'discarded' => 'bg-gray-400',
        'hired' => 'bg-emerald-500',
        default => 'bg-primary-500',
    };
@endphp
<a href="{{ route('candidate.applications.show', $application) }}">
    <x-ui.card padding="md" class="hover:border-primary-300 transition-all duration-200 cursor-pointer h-full">
        <div class="space-y-4">
            <div class="flex items-start justify-between">
                <div class="flex-1 min-w-0">
                    <h3 class="text-lg font-semibold text-gray-900 mb-1 truncate">
                        {{ $application->position?->title ?? 'Position' }}
                    </h3>
                    <p class="text-sm text-gray-600 truncate">
                        {{ $application->position?->location }}
                    </p>
                </div>
                <x-ui.status-badge :status="$application->status" size="sm" />
            </div>

            <div class="flex items-center text-sm text-gray-500">
                <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                Applied {{ $application->created_at->diffForHumans() }}
            </div>

            <div class="relative pt-2">
                <div class="overflow-hidden h-2 text-xs flex rounded-full bg-gray-200">
                    <div style="width: {{ $progress }}%" class="{{ $barClass }} transition-all duration-500"></div>
                </div>
                <p class="text-xs text-gray-500 mt-1">{{ $progress }}% Complete</p>
            </div>
        </div>
    </x-ui.card>
</a>
