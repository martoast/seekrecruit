@extends('layouts.admin')

@section('title', 'Add HR Admin - Admin')

@section('content')
    <div class="min-h-screen">
        <div class="mb-6">
            <a href="{{ route('admin.admins.index') }}" class="inline-flex items-center gap-2 text-sm font-medium text-gray-600 hover:text-gray-900">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                Back to Admins
            </a>
        </div>

        <div class="mb-8">
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight">Add HR Admin</h1>
            <p class="mt-1 text-gray-500">Create a new HR admin and assign them to a client</p>
        </div>

        @if ($clients->isEmpty())
            <x-ui.alert type="warning">
                There are no active clients yet. <a href="{{ route('admin.clients.create') }}" class="font-semibold underline">Create a client</a> first so you can assign an admin to it.
            </x-ui.alert>
        @else
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden max-w-2xl">
                <form method="POST" action="{{ route('admin.admins.store') }}" class="divide-y divide-gray-100">
                    @csrf
                    <div class="p-6 space-y-6">
                        <x-ui.input label="Name" name="name" placeholder="e.g., Maria Garcia" required />
                        <x-ui.input label="Email" name="email" type="email" placeholder="maria@example.com" required />
                        <x-ui.select
                            label="Client"
                            name="client_id"
                            required
                            placeholder="Assign to a client..."
                            :value="old('client_id')"
                            :options="$clients->pluck('name', 'id')->all()"
                        />
                        <x-ui.input label="Password" name="password" type="password" placeholder="At least 8 characters" required />
                        <x-ui.input label="Confirm Password" name="password_confirmation" type="password" required />
                    </div>

                    <div class="px-6 py-4 bg-gray-50 flex flex-col-reverse sm:flex-row justify-end gap-3">
                        <a href="{{ route('admin.admins.index') }}">
                            <x-ui.button type="button" variant="secondary">Cancel</x-ui.button>
                        </a>
                        <x-ui.button type="submit" variant="primary">Create HR Admin</x-ui.button>
                    </div>
                </form>
            </div>
        @endif
    </div>
@endsection
