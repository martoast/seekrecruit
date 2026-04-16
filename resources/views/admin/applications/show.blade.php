@extends('layouts.admin')

@section('title', ($application->candidate?->user?->name ?? 'Application') . ' - Admin')

@section('content')
    @php
        $name = $application->candidate?->user?->name ?? 'Unknown';
        $initials = collect(explode(' ', $name))->take(2)->map(fn ($p) => mb_substr($p, 0, 1))->implode('') ?: '?';
        $statuses = [
            'registered' => 'Registered',
            'preselected' => 'Preselected',
            'interview' => 'Interview',
            'evaluation' => 'Evaluation',
            'finalist' => 'Finalist',
            'hired' => 'Hired',
            'discarded' => 'Discarded',
        ];
    @endphp

    <div class="min-h-screen">
        <div class="mb-6">
            <a href="{{ route('admin.applications.index') }}" class="inline-flex items-center gap-2 text-sm font-medium text-gray-600 hover:text-gray-900">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                Back to Applications
            </a>
        </div>

        <div class="space-y-6">
            {{-- Header --}}
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <div class="p-6">
                    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                        <div class="flex items-start gap-4">
                            <div class="w-14 h-14 rounded-xl bg-primary-100 text-primary-700 flex items-center justify-center text-xl font-bold">
                                {{ strtoupper($initials) }}
                            </div>
                            <div>
                                <h1 class="text-xl sm:text-2xl font-bold text-gray-900">{{ $name }}</h1>
                                <p class="text-gray-500">{{ $application->candidate?->user?->email }}</p>
                                <p class="text-sm text-gray-600 mt-1">
                                    Applied for <span class="font-medium text-gray-900">{{ $application->position?->title }}</span>
                                </p>
                            </div>
                        </div>
                        <x-ui.status-badge :status="$application->status" />
                    </div>
                </div>

                {{-- Status update --}}
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
                    <p class="text-sm font-medium text-gray-700 mb-3">Update Status</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach ($statuses as $value => $label)
                            <form method="POST" action="{{ route('admin.applications.status', $application) }}">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="{{ $value }}" />
                                <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-sm font-medium transition-all {{ $application->status->value === $value ? 'bg-primary-500 text-white shadow-sm' : 'bg-white text-gray-700 border border-gray-200 hover:border-gray-300 hover:bg-gray-50' }}">
                                    {{ $label }}
                                </button>
                            </form>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 space-y-6">
                    {{-- Candidate profile summary --}}
                    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                            <h2 class="text-lg font-semibold text-gray-900">Candidate Profile</h2>
                            @if ($application->candidate)
                                <a href="{{ route('admin.candidates.show', $application->candidate) }}" class="text-sm font-medium text-primary-600 hover:text-primary-700">View Full Profile</a>
                            @endif
                        </div>
                        <div class="p-6">
                            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">University</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $application->candidate?->university ?: 'Not specified' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Degree</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $application->candidate?->degree ?: 'Not specified' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Location</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $application->candidate?->location ?: 'Not specified' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">CV Status</dt>
                                    <dd class="mt-1">
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium {{ $application->candidate?->cv_path ? 'bg-emerald-50 text-emerald-700 ring-1 ring-inset ring-emerald-600/20' : 'bg-amber-50 text-amber-700 ring-1 ring-inset ring-amber-600/20' }}">
                                            {{ $application->candidate?->cv_path ? 'Uploaded' : 'Missing' }}
                                        </span>
                                    </dd>
                                </div>
                            </dl>

                            @if (is_array($application->candidate?->skills) && count($application->candidate->skills))
                                <div class="mt-6 pt-6 border-t border-gray-100">
                                    <dt class="text-sm font-medium text-gray-500 mb-2">Skills</dt>
                                    <dd class="flex flex-wrap gap-2">
                                        @foreach ($application->candidate->skills as $skill)
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-gray-100 text-gray-700">{{ $skill }}</span>
                                        @endforeach
                                    </dd>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Interviews --}}
                    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                            <h2 class="text-lg font-semibold text-gray-900">Interviews</h2>
                            <button type="button" data-open-schedule-modal class="text-sm font-medium text-primary-600 hover:text-primary-700 inline-flex items-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                                Schedule
                            </button>
                        </div>
                        @if ($application->interviews->count())
                            <div class="divide-y divide-gray-100">
                                @foreach ($application->interviews as $interview)
                                    @php $upcoming = $interview->scheduled_at->isFuture(); @endphp
                                    <div class="p-4">
                                        <div class="flex items-start justify-between">
                                            <div class="flex items-start gap-3">
                                                <div class="w-10 h-10 rounded-lg {{ $upcoming ? 'bg-blue-100' : 'bg-gray-100' }} flex items-center justify-center">
                                                    <svg class="w-5 h-5 {{ $upcoming ? 'text-blue-600' : 'text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                                </div>
                                                <div>
                                                    <p class="font-medium text-gray-900">{{ ucfirst($interview->type->value) }} Interview</p>
                                                    <p class="text-sm text-gray-500">{{ $interview->scheduled_at->format('M j, Y \a\t g:i A') }}</p>
                                                    @if ($interview->location)
                                                        <p class="text-sm text-gray-500">{{ $interview->location }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $upcoming ? 'bg-blue-50 text-blue-700 ring-1 ring-inset ring-blue-600/20' : 'bg-gray-100 text-gray-600' }}">
                                                {{ $upcoming ? 'Upcoming' : 'Completed' }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="p-8 text-center">
                                <p class="text-sm text-gray-500 mb-3">No interviews scheduled yet</p>
                                <button type="button" data-open-schedule-modal>
                                    <x-ui.button variant="primary" size="sm">Schedule Interview</x-ui.button>
                                </button>
                            </div>
                        @endif
                    </div>

                    {{-- Notes --}}
                    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100">
                            <h2 class="text-lg font-semibold text-gray-900">Internal Notes</h2>
                        </div>
                        <div class="p-6">
                            <form method="POST" action="{{ route('admin.applications.notes.store', $application) }}" class="mb-6">
                                @csrf
                                <x-ui.textarea name="content" placeholder="Add a note about this application..." :rows="3" required />
                                <div class="flex justify-end mt-3">
                                    <x-ui.button type="submit" variant="primary">Add Note</x-ui.button>
                                </div>
                            </form>

                            @if ($application->notes->count())
                                <div class="space-y-4">
                                    @foreach ($application->notes as $note)
                                        <div class="p-4 bg-gray-50 rounded-xl">
                                            <div class="flex items-center justify-between mb-2">
                                                <span class="font-medium text-gray-900 text-sm">{{ $note->author?->name }}</span>
                                                <span class="text-xs text-gray-500">{{ $note->created_at->diffForHumans() }}</span>
                                            </div>
                                            <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ $note->content }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-sm text-gray-500 text-center py-4">No notes yet. Add one above.</p>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Sidebar --}}
                <div class="space-y-6">
                    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100">
                            <h3 class="text-lg font-semibold text-gray-900">Timeline</h3>
                        </div>
                        <div class="p-6 space-y-4">
                            <div class="flex items-start gap-3">
                                <div class="w-2 h-2 mt-2 bg-primary-500 rounded-full"></div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Application Submitted</p>
                                    <p class="text-xs text-gray-500">{{ $application->created_at->format('M j, Y \a\t g:i A') }}</p>
                                </div>
                            </div>
                            @if ($application->updated_at->ne($application->created_at))
                                <div class="flex items-start gap-3">
                                    <div class="w-2 h-2 mt-2 bg-gray-400 rounded-full"></div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">Last Updated</p>
                                        <p class="text-xs text-gray-500">{{ $application->updated_at->format('M j, Y \a\t g:i A') }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100">
                            <h3 class="text-lg font-semibold text-gray-900">Position</h3>
                        </div>
                        <div class="p-6">
                            <p class="font-medium text-gray-900">{{ $application->position?->title }}</p>
                            <p class="text-sm text-gray-500 mt-1">{{ $application->position?->location }}</p>
                            <div class="mt-3">
                                @php
                                    $positionStatus = $application->position?->status?->value;
                                    $positionVariant = match ($positionStatus) {
                                        'open' => 'success',
                                        'draft' => 'warning',
                                        default => 'default',
                                    };
                                @endphp
                                <x-ui.badge :variant="$positionVariant">
                                    {{ ucfirst($positionStatus ?? 'unknown') }}
                                </x-ui.badge>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Schedule Interview modal --}}
    <div data-schedule-modal class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="fixed inset-0 bg-black/40 backdrop-blur-sm" data-close-modal></div>
            <div class="relative z-10 bg-white rounded-2xl shadow-2xl w-full max-w-2xl">
                <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="text-xl font-semibold text-gray-900">Schedule Interview</h3>
                    <button type="button" data-close-modal class="text-gray-400 hover:text-gray-600 rounded-lg p-1 hover:bg-gray-100">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
                <form method="POST" action="{{ route('admin.interviews.store') }}" class="px-6 py-5 space-y-6">
                    @csrf
                    <input type="hidden" name="application_id" value="{{ $application->id }}" />

                    <div class="p-4 bg-gray-50 rounded-xl text-sm text-gray-700">
                        Scheduling interview for
                        <span class="font-medium text-gray-900">{{ $application->candidate?->user?->name }}</span>
                        —
                        <span class="font-medium text-gray-900">{{ $application->position?->title }}</span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <x-ui.input label="Date & Time" name="scheduled_at" type="datetime-local" required />
                        <x-ui.select label="Interview Type" name="type" required
                            :options="['technical' => 'Technical', 'hr' => 'HR', 'final' => 'Final']" />
                    </div>
                    <x-ui.input label="Location" name="location" placeholder="Conference Room A, Zoom link, etc." />
                    <x-ui.textarea label="Internal Notes" name="notes" placeholder="Preparation notes..." :rows="3" />

                    <div class="flex flex-col-reverse sm:flex-row justify-end gap-3 pt-4 border-t border-gray-100">
                        <button type="button" data-close-modal class="px-5 py-2.5 text-base rounded-xl font-medium bg-white text-primary-700 border-2 border-primary-200 hover:bg-primary-50">Cancel</button>
                        <x-ui.button type="submit" variant="primary">Schedule Interview</x-ui.button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        (function () {
            const modal = document.querySelector('[data-schedule-modal]');
            const open = () => modal.classList.remove('hidden');
            const close = () => modal.classList.add('hidden');
            document.querySelectorAll('[data-open-schedule-modal]').forEach((b) => b.addEventListener('click', open));
            modal.querySelectorAll('[data-close-modal]').forEach((b) => b.addEventListener('click', close));
        })();
    </script>
@endsection
