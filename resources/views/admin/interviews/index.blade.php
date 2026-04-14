@extends('layouts.admin')

@section('title', 'Interviews - Admin')

@section('content')
    @php
        $applicationOptions = $applications->mapWithKeys(function ($app) {
            $name = $app->candidate?->user?->name ?? 'Unknown';
            $position = $app->position?->title ?? 'Unknown Position';
            return [$app->id => "{$name} — {$position}"];
        })->all();

        $typeOptions = [
            'technical' => 'Technical',
            'hr' => 'HR',
            'final' => 'Final',
        ];

        $total = $interviews->count();
        $upcoming = $interviews->filter(fn ($i) => $i->scheduled_at->isFuture())->count();
        $technical = $interviews->where('type.value', 'technical')->count();
        $final = $interviews->where('type.value', 'final')->count();
    @endphp

    <div class="min-h-screen">
        <div class="mb-8">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div class="min-w-0">
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight">Interviews</h1>
                    <p class="mt-1 text-gray-500 text-sm sm:text-base">Schedule and manage candidate interviews</p>
                </div>
                <button type="button" data-open-create class="inline-flex items-center justify-center gap-2 px-5 py-2.5 text-base rounded-xl font-medium bg-primary-500 text-white hover:bg-primary-600 shadow-sm hover:shadow-md">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                    Schedule Interview
                </button>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mt-6">
                <div class="bg-white rounded-xl border border-gray-200 p-4">
                    <p class="text-xs text-gray-500 uppercase tracking-wide">Total</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $total }}</p>
                </div>
                <div class="bg-white rounded-xl border border-gray-200 p-4">
                    <p class="text-xs text-gray-500 uppercase tracking-wide">Upcoming</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $upcoming }}</p>
                </div>
                <div class="bg-white rounded-xl border border-gray-200 p-4">
                    <p class="text-xs text-gray-500 uppercase tracking-wide">Technical</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $technical }}</p>
                </div>
                <div class="bg-white rounded-xl border border-gray-200 p-4">
                    <p class="text-xs text-gray-500 uppercase tracking-wide">Final Round</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $final }}</p>
                </div>
            </div>
        </div>

        {{-- Filters --}}
        <form method="GET" class="bg-white rounded-xl border border-gray-200 p-4 mb-6">
            <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                <x-ui.select
                    label="Interview Type"
                    name="type"
                    placeholder="All Types"
                    :value="request('type')"
                    :options="$typeOptions"
                />
                <x-ui.input label="From Date" name="from_date" type="date" :value="request('from_date')" />
                <x-ui.input label="To Date" name="to_date" type="date" :value="request('to_date')" />
                <div class="flex items-end gap-2">
                    <x-ui.button type="submit" variant="primary">Filter</x-ui.button>
                    <a href="{{ route('admin.interviews.index') }}">
                        <x-ui.button type="button" variant="ghost">Clear</x-ui.button>
                    </a>
                </div>
            </div>
        </form>

        @if ($interviews->isEmpty())
            <div class="bg-white rounded-xl border border-gray-200 p-12">
                <div class="text-center max-w-md mx-auto">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">No interviews scheduled</h3>
                    <p class="text-gray-500 mb-6">Get started by scheduling your first interview with a candidate.</p>
                    <button type="button" data-open-create class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl font-medium bg-primary-500 text-white hover:bg-primary-600">
                        Schedule Interview
                    </button>
                </div>
            </div>
        @else
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Candidate</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Position</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Type</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Schedule</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Location</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach ($interviews as $interview)
                                @php $upcoming = $interview->scheduled_at->isFuture(); @endphp
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <p class="font-medium text-gray-900">{{ $interview->application?->candidate?->user?->name ?? '—' }}</p>
                                        <p class="text-sm text-gray-500">{{ $interview->application?->candidate?->user?->email }}</p>
                                    </td>
                                    <td class="px-6 py-4">
                                        <p class="text-sm font-medium text-gray-900">{{ $interview->application?->position?->title ?? '—' }}</p>
                                        <p class="text-sm text-gray-500">{{ $interview->application?->position?->location }}</p>
                                    </td>
                                    <td class="px-6 py-4">
                                        <x-ui.status-badge :status="$interview->type->value" type="interview" size="sm" />
                                    </td>
                                    <td class="px-6 py-4">
                                        <p class="text-sm font-medium text-gray-900">{{ $interview->scheduled_at->format('M j, Y') }}</p>
                                        <p class="text-sm text-gray-500">{{ $interview->scheduled_at->format('g:i A') }}</p>
                                    </td>
                                    <td class="px-6 py-4">
                                        <p class="text-sm text-gray-700">{{ $interview->location ?: '—' }}</p>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium {{ $upcoming ? 'bg-blue-50 text-blue-700 ring-1 ring-inset ring-blue-600/20' : 'bg-gray-100 text-gray-600' }}">
                                            {{ $upcoming ? 'Upcoming' : 'Completed' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center justify-end gap-1">
                                            <button type="button" data-edit-interview="{{ $interview->id }}"
                                                data-application-id="{{ $interview->application_id }}"
                                                data-scheduled="{{ $interview->scheduled_at->format('Y-m-d\TH:i') }}"
                                                data-type="{{ $interview->type->value }}"
                                                data-location="{{ e($interview->location) }}"
                                                data-notes="{{ e($interview->notes) }}"
                                                class="p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                            </button>
                                            <form method="POST" action="{{ route('admin.interviews.destroy', $interview) }}" onsubmit="return confirm('Are you sure you want to delete this interview?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-2 text-gray-500 hover:text-red-600 hover:bg-red-50 rounded-lg">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                                </button>
                                            </form>
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

    {{-- Create/Edit Modal --}}
    <div data-interview-modal class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="fixed inset-0 bg-black/40 backdrop-blur-sm" data-close-modal></div>
            <div class="relative z-10 bg-white rounded-2xl shadow-2xl w-full max-w-2xl">
                <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="text-xl font-semibold text-gray-900" data-modal-title>Schedule Interview</h3>
                    <button type="button" data-close-modal class="text-gray-400 hover:text-gray-600 rounded-lg p-1 hover:bg-gray-100">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
                <form method="POST" data-interview-form class="px-6 py-5 space-y-6">
                    @csrf
                    <div>
                        <x-ui.select
                            label="Application"
                            name="application_id"
                            placeholder="Select a candidate application"
                            :options="$applicationOptions"
                            required
                        />
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <x-ui.input label="Date & Time" name="scheduled_at" type="datetime-local" required />
                        <x-ui.select label="Interview Type" name="type" :options="$typeOptions" required />
                    </div>
                    <x-ui.input label="Location" name="location" placeholder="Conference Room A, Zoom link, etc." />
                    <x-ui.textarea label="Internal Notes" name="notes" placeholder="Preparation notes..." :rows="3" />
                    <div class="flex flex-col-reverse sm:flex-row justify-end gap-3 pt-4 border-t border-gray-100">
                        <button type="button" data-close-modal class="px-5 py-2.5 text-base rounded-xl font-medium bg-white text-primary-700 border-2 border-primary-200 hover:bg-primary-50">Cancel</button>
                        <x-ui.button type="submit" variant="primary" data-submit-label>Schedule Interview</x-ui.button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        (function () {
            const modal = document.querySelector('[data-interview-modal]');
            const form = modal.querySelector('[data-interview-form]');
            const title = modal.querySelector('[data-modal-title]');
            const submitLabel = modal.querySelector('[data-submit-label]');

            const openModal = () => modal.classList.remove('hidden');
            const closeModal = () => modal.classList.add('hidden');

            document.querySelectorAll('[data-open-create]').forEach((btn) => {
                btn.addEventListener('click', () => {
                    form.action = '{{ route('admin.interviews.store') }}';
                    form.querySelector('input[name="_method"]')?.remove();
                    title.textContent = 'Schedule Interview';
                    submitLabel.textContent = 'Schedule Interview';
                    form.reset();
                    form.querySelector('[name="application_id"]').disabled = false;
                    openModal();
                });
            });

            document.querySelectorAll('[data-edit-interview]').forEach((btn) => {
                btn.addEventListener('click', () => {
                    const id = btn.dataset.editInterview;
                    form.action = `/admin/interviews/${id}`;

                    let methodField = form.querySelector('input[name="_method"]');
                    if (!methodField) {
                        methodField = document.createElement('input');
                        methodField.type = 'hidden';
                        methodField.name = '_method';
                        form.appendChild(methodField);
                    }
                    methodField.value = 'PUT';

                    title.textContent = 'Edit Interview';
                    submitLabel.textContent = 'Update Interview';

                    form.querySelector('[name="application_id"]').value = btn.dataset.applicationId;
                    form.querySelector('[name="application_id"]').disabled = true;
                    form.querySelector('[name="scheduled_at"]').value = btn.dataset.scheduled;
                    form.querySelector('[name="type"]').value = btn.dataset.type;
                    form.querySelector('[name="location"]').value = btn.dataset.location || '';
                    form.querySelector('[name="notes"]').value = btn.dataset.notes || '';

                    openModal();
                });
            });

            modal.querySelectorAll('[data-close-modal]').forEach((el) => el.addEventListener('click', closeModal));
        })();
    </script>
@endsection
