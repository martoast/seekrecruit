@extends('layouts.admin')

@section('title', 'Edit ' . $client->name . ' - Admin')

@section('content')
    <div class="min-h-screen">
        <div class="mb-6">
            <a href="{{ route('admin.clients.index') }}" class="inline-flex items-center gap-2 text-sm font-medium text-gray-600 hover:text-gray-900">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                Back to Clients
            </a>
        </div>

        <div class="mb-8">
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight">Edit Client</h1>
            <p class="mt-1 text-gray-500">Update client details and settings</p>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden max-w-2xl">
            <form method="POST" action="{{ route('admin.clients.update', $client) }}" class="divide-y divide-gray-100">
                @csrf
                @method('PUT')
                <div class="p-6 space-y-6">
                    <x-ui.input label="Name" name="name" :value="$client->name" required />
                    <x-ui.input label="Slug" name="slug" :value="$client->slug" required />
                    <x-ui.input label="Industry" name="industry" :value="$client->industry" />
                    <x-ui.select
                        label="Status"
                        name="is_active"
                        required
                        :value="old('is_active', $client->is_active ? 1 : 0)"
                        :options="[1 => 'Active', 0 => 'Inactive']"
                    />
                </div>

                <div class="px-6 py-4 bg-gray-50 flex flex-col sm:flex-row justify-between gap-4">
                    <button type="submit" form="delete-client-form" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-red-600 hover:text-red-700 hover:bg-red-50 rounded-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                        Archive Client
                    </button>

                    <div class="flex flex-col-reverse sm:flex-row gap-3">
                        <a href="{{ route('admin.clients.index') }}">
                            <x-ui.button type="button" variant="secondary">Cancel</x-ui.button>
                        </a>
                        <x-ui.button type="submit" variant="primary">Save Changes</x-ui.button>
                    </div>
                </div>
            </form>
        </div>

        {{-- Logo uploader --}}
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden max-w-2xl mt-6">
            <div class="p-6 space-y-4">
                <h3 class="text-lg font-semibold text-gray-900">Logo</h3>
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0">
                        @if ($client->logo_url)
                            <img src="{{ $client->logo_url }}" alt="{{ $client->name }} logo" class="w-20 h-20 rounded-xl object-contain bg-white border border-gray-200 p-1" />
                        @else
                            <div class="w-20 h-20 rounded-xl bg-gray-100 border-2 border-dashed border-gray-300 flex items-center justify-center">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                            </div>
                        @endif
                    </div>
                    <div class="flex-1 space-y-2">
                        <form method="POST" action="{{ route('admin.clients.logo.store', $client) }}" enctype="multipart/form-data">
                            @csrf
                            <label class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 cursor-pointer w-fit">
                                <input type="file" name="logo" accept="image/jpeg,image/png,image/jpg,image/webp,image/svg+xml" class="sr-only" onchange="this.form.submit()" />
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" /></svg>
                                {{ $client->logo_url ? 'Change Logo' : 'Upload Logo' }}
                            </label>
                            <p class="mt-1 text-xs text-gray-500">JPEG, PNG, WebP, SVG. Max 2MB. Square works best.</p>
                        </form>
                        @if ($client->logo_url)
                            <form method="POST" action="{{ route('admin.clients.logo.destroy', $client) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-sm text-red-600 hover:text-red-700 font-medium">Remove logo</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <form id="delete-client-form" method="POST" action="{{ route('admin.clients.destroy', $client) }}" onsubmit="return confirm('Archive this client? Positions will be hidden; applications stay in the history. You can restore the client from the database if needed.');" class="hidden">
            @csrf
            @method('DELETE')
        </form>
    </div>
@endsection
