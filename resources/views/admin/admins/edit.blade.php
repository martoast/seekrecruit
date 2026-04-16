@extends('layouts.admin')

@section('title', 'Edit ' . $user->name . ' - Admin')

@section('content')
    <div class="min-h-screen">
        <div class="mb-6">
            <a href="{{ route('admin.admins.index') }}" class="inline-flex items-center gap-2 text-sm font-medium text-gray-600 hover:text-gray-900">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                Back to Admins
            </a>
        </div>

        <div class="mb-8">
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight">Edit HR Admin</h1>
            <p class="mt-1 text-gray-500">Update details or reassign to a different client</p>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden max-w-2xl">
            <form method="POST" action="{{ route('admin.admins.update', $user) }}" class="divide-y divide-gray-100">
                @csrf
                @method('PUT')
                <div class="p-6 space-y-6">
                    <x-ui.input label="Name" name="name" :value="$user->name" required />
                    <x-ui.input label="Email" name="email" type="email" :value="$user->email" required />
                    <x-ui.select
                        label="Client"
                        name="client_id"
                        required
                        :value="old('client_id', $user->client_id)"
                        :options="$clients->pluck('name', 'id')->all()"
                    />

                    <div class="pt-4 border-t border-gray-100">
                        <p class="text-sm text-gray-600 mb-4">Leave password fields blank to keep the current password.</p>
                        <x-ui.input label="New Password" name="password" type="password" placeholder="Optional — at least 8 characters" />
                        <div class="mt-4">
                            <x-ui.input label="Confirm Password" name="password_confirmation" type="password" />
                        </div>
                    </div>
                </div>

                <div class="px-6 py-4 bg-gray-50 flex flex-col-reverse sm:flex-row justify-end gap-3">
                    <a href="{{ route('admin.admins.index') }}">
                        <x-ui.button type="button" variant="secondary">Cancel</x-ui.button>
                    </a>
                    <x-ui.button type="submit" variant="primary">Save Changes</x-ui.button>
                </div>
            </form>
        </div>
    </div>
@endsection
