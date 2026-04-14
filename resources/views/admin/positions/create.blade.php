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
                    <x-ui.input label="Position Title" name="title" placeholder="e.g., Junior Software Developer" required />

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <x-ui.input label="Location" name="location" placeholder="e.g., Tijuana, Remote" required />
                        <x-ui.input label="Company / Organization Name" name="company_name" placeholder="e.g., Acme Corp" />
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

                    <label class="flex items-start gap-3">
                        <input type="hidden" name="is_active" value="0" />
                        <input type="checkbox" name="is_active" value="1" checked class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded mt-1" />
                        <div>
                            <span class="text-sm font-medium text-gray-900">Active Position</span>
                            <p class="text-sm text-gray-500">When enabled, this position will be visible to candidates and open for applications.</p>
                        </div>
                    </label>
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
