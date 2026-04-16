@extends('layouts.admin')

@section('title', 'Add Client - Admin')

@section('content')
    <div class="min-h-screen">
        <div class="mb-6">
            <a href="{{ route('admin.clients.index') }}" class="inline-flex items-center gap-2 text-sm font-medium text-gray-600 hover:text-gray-900">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                Back to Clients
            </a>
        </div>

        <div class="mb-8">
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight">Add Client</h1>
            <p class="mt-1 text-gray-500">Create a new client company</p>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden max-w-2xl">
            <form method="POST" action="{{ route('admin.clients.store') }}" class="divide-y divide-gray-100">
                @csrf
                <div class="p-6 space-y-6">
                    <x-ui.input label="Name" name="name" placeholder="e.g., JAE Tijuana" required />

                    <x-ui.input
                        label="Slug"
                        name="slug"
                        placeholder="auto-generated from name"
                        :value="old('slug')"
                    />

                    <x-ui.input label="Industry" name="industry" placeholder="e.g., Manufacturing" />

                    <x-ui.select
                        label="Status"
                        name="is_active"
                        required
                        :value="old('is_active', 1)"
                        :options="[1 => 'Active', 0 => 'Inactive']"
                    />

                    <div class="p-4 bg-blue-50 rounded-xl border border-blue-100">
                        <div class="flex gap-3">
                            <svg class="w-5 h-5 text-blue-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            <div>
                                <p class="text-sm font-medium text-blue-900">Logo can be added after creation</p>
                                <p class="text-sm text-blue-700 mt-0.5">You'll be able to upload the client logo once the record is saved.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="px-6 py-4 bg-gray-50 flex flex-col-reverse sm:flex-row justify-end gap-3">
                    <a href="{{ route('admin.clients.index') }}">
                        <x-ui.button type="button" variant="secondary">Cancel</x-ui.button>
                    </a>
                    <x-ui.button type="submit" variant="primary">Create Client</x-ui.button>
                </div>
            </form>
        </div>
    </div>
@endsection
