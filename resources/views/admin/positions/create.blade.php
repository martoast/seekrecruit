@extends('layouts.admin')

@section('title', 'Create Position - Admin')

@section('content')
    <div class="min-h-screen">
        <div class="mb-6">
            <a href="{{ route('admin.positions.index') }}" class="inline-flex items-center gap-2 text-sm font-medium text-gray-600 hover:text-gray-900">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                Back to Positions
            </a>
        </div>

        <div class="mb-8">
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight">Create Position</h1>
            <p class="mt-1 text-gray-500">Add a new job position for candidates to apply</p>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden max-w-3xl">
            <form method="POST" action="{{ route('admin.positions.store') }}" class="divide-y divide-gray-100">
                @csrf
                <div class="p-6 space-y-6">
                    @if (auth()->user()->isSuperAdmin())
                        <x-ui.select
                            label="Client"
                            name="client_id"
                            required
                            placeholder="Choose a client..."
                            :value="old('client_id')"
                            :options="$clients->pluck('name', 'id')->all()"
                        />
                    @else
                        {{-- HR admin: client_id is auto-filled in PositionRequest::prepareForValidation --}}
                        <div class="p-3 bg-gray-50 rounded-lg text-sm text-gray-700">
                            Creating for <span class="font-semibold">{{ auth()->user()->client?->name }}</span>
                        </div>
                    @endif

                    <x-ui.input label="Position Title" name="title" placeholder="e.g., Junior Software Developer" required />

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <x-ui.input label="Location" name="location" placeholder="e.g., Tijuana" required />
                        <x-ui.input label="Company / Organization Name" name="company_name" placeholder="e.g., Acme Corp" />
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                        <x-ui.select
                            label="Employment Type"
                            name="employment_type"
                            required
                            :value="old('employment_type', 'full_time')"
                            :options="[
                                'full_time' => 'Full-time',
                                'part_time' => 'Part-time',
                                'internship' => 'Internship',
                                'contract' => 'Contract',
                            ]"
                        />
                        <x-ui.select
                            label="Work Modality"
                            name="modality"
                            required
                            :value="old('modality', 'on_site')"
                            :options="[
                                'on_site' => 'On-site',
                                'remote' => 'Remote',
                                'hybrid' => 'Hybrid',
                            ]"
                        />
                        <x-ui.select
                            label="Status"
                            name="status"
                            required
                            :value="old('status', 'open')"
                            :options="[
                                'open' => 'Open',
                                'closed' => 'Closed',
                                'draft' => 'Draft',
                            ]"
                        />
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                        <x-ui.input label="Salary Minimum" name="salary_min" type="number" placeholder="e.g., 30000" />
                        <x-ui.input label="Salary Maximum" name="salary_max" type="number" placeholder="e.g., 45000" />
                        <x-ui.select
                            label="Currency"
                            name="salary_currency"
                            :value="old('salary_currency', 'MXN')"
                            :options="[
                                'MXN' => 'MXN (Mexican Peso)',
                                'USD' => 'USD (US Dollar)',
                            ]"
                        />
                    </div>

                    <div class="p-4 bg-blue-50 rounded-xl border border-blue-100">
                        <div class="flex gap-3">
                            <svg class="w-5 h-5 text-blue-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            <div>
                                <p class="text-sm font-medium text-blue-900">Position images can be added after creation</p>
                                <p class="text-sm text-blue-700 mt-0.5">You'll be able to upload a cover image and company logo when editing this position.</p>
                            </div>
                        </div>
                    </div>

                    <x-ui.textarea label="Description" name="description" :rows="5" placeholder="Describe the position, responsibilities, and what the candidate will be working on..." required />
                    <x-ui.textarea label="Requirements" name="requirements" :rows="5" placeholder="List the required qualifications, skills, and experience..." required />
                </div>

                <div class="px-6 py-4 bg-gray-50 flex flex-col-reverse sm:flex-row justify-end gap-3">
                    <a href="{{ route('admin.positions.index') }}">
                        <x-ui.button type="button" variant="secondary">Cancel</x-ui.button>
                    </a>
                    <x-ui.button type="submit" variant="primary">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                        Create Position
                    </x-ui.button>
                </div>
            </form>
        </div>
    </div>
@endsection
