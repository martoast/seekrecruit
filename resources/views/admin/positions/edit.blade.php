@extends('layouts.admin')

@section('title', 'Edit ' . $position->title . ' - Admin')

@section('content')
    <div class="min-h-screen">
        <div class="mb-6">
            <a href="{{ route('admin.positions.index') }}" class="inline-flex items-center gap-2 text-sm font-medium text-gray-600 hover:text-gray-900">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                Back to Positions
            </a>
        </div>

        <div class="mb-8">
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight">Edit Position</h1>
            <p class="mt-1 text-gray-500">Update position details and settings</p>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden max-w-3xl">
            <form method="POST" action="{{ route('admin.positions.update', $position) }}" class="divide-y divide-gray-100">
                @csrf
                @method('PUT')
                <div class="p-6 space-y-6">
                    @if (auth()->user()->isSuperAdmin())
                        <x-ui.select
                            label="Client"
                            name="client_id"
                            required
                            :value="old('client_id', $position->client_id)"
                            :options="$clients->pluck('name', 'id')->all()"
                        />
                    @else
                        <div class="p-3 bg-gray-50 rounded-lg text-sm text-gray-700">
                            Client: <span class="font-semibold">{{ $position->client?->name ?? '—' }}</span>
                        </div>
                    @endif

                    <x-ui.input label="Position Title" name="title" :value="$position->title" required />

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <x-ui.input label="Location" name="location" :value="$position->location" required />
                        <x-ui.input label="Company / Organization Name" name="company_name" :value="$position->company_name" />
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                        <x-ui.select
                            label="Employment Type"
                            name="employment_type"
                            required
                            :value="old('employment_type', $position->employment_type?->value)"
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
                            :value="old('modality', $position->modality?->value)"
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
                            :value="old('status', $position->status?->value)"
                            :options="[
                                'open' => 'Open',
                                'closed' => 'Closed',
                                'draft' => 'Draft',
                            ]"
                        />
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                        <x-ui.input label="Salary Minimum" name="salary_min" type="number" :value="$position->salary_min" placeholder="e.g., 30000" />
                        <x-ui.input label="Salary Maximum" name="salary_max" type="number" :value="$position->salary_max" placeholder="e.g., 45000" />
                        <x-ui.select
                            label="Currency"
                            name="salary_currency"
                            :value="old('salary_currency', $position->salary_currency ?? 'MXN')"
                            :options="[
                                'MXN' => 'MXN (Mexican Peso)',
                                'USD' => 'USD (US Dollar)',
                            ]"
                        />
                    </div>

                    <x-ui.textarea label="Description" name="description" :rows="5" :value="$position->description" required />
                    <x-ui.textarea label="Requirements" name="requirements" :rows="5" :value="$position->requirements" required />
                </div>

                <div class="px-6 py-4 bg-gray-50 flex flex-col sm:flex-row justify-between gap-4">
                    <button type="submit" form="delete-position-form" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-red-600 hover:text-red-700 hover:bg-red-50 rounded-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                        Delete Position
                    </button>
                    <div class="flex flex-col-reverse sm:flex-row gap-3">
                        <a href="{{ route('admin.positions.index') }}">
                            <x-ui.button type="button" variant="secondary">Cancel</x-ui.button>
                        </a>
                        <x-ui.button type="submit" variant="primary">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                            Update Position
                        </x-ui.button>
                    </div>
                </div>
            </form>
        </div>

        {{-- Images section --}}
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden max-w-3xl mt-6">
            <div class="p-6 space-y-6">
                <h3 class="text-lg font-semibold text-gray-900">Images</h3>

                {{-- Position image --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-3">Position Cover Image</label>
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0">
                            @if ($position->image_url)
                                <img src="{{ $position->image_url }}" alt="Position image" class="w-32 h-24 rounded-xl object-cover border border-gray-200" />
                            @else
                                <div class="w-32 h-24 rounded-xl bg-gray-100 border-2 border-dashed border-gray-300 flex items-center justify-center">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                </div>
                            @endif
                        </div>
                        <div class="flex-1 space-y-2">
                            <form method="POST" action="{{ route('admin.positions.image.store', $position) }}" enctype="multipart/form-data" class="flex flex-col gap-2">
                                @csrf
                                <label class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 cursor-pointer w-fit">
                                    <input type="file" name="image" accept="image/jpeg,image/png,image/jpg,image/webp" class="sr-only" onchange="this.form.submit()" />
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" /></svg>
                                    {{ $position->image_url ? 'Change Image' : 'Upload Image' }}
                                </label>
                                <p class="text-xs text-gray-500">JPEG, PNG, WebP. Max 5MB. Recommended: 1200x630px</p>
                            </form>
                            @if ($position->image_url)
                                <form method="POST" action="{{ route('admin.positions.image.destroy', $position) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-sm text-red-600 hover:text-red-700 font-medium">Remove image</button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Company logo --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-3">Company Logo</label>
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0">
                            @if ($position->company_logo_url)
                                <img src="{{ $position->company_logo_url }}" alt="Company logo" class="w-16 h-16 rounded-xl object-contain bg-white border border-gray-200 p-1" />
                            @else
                                <div class="w-16 h-16 rounded-xl bg-gray-100 border-2 border-dashed border-gray-300 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                                </div>
                            @endif
                        </div>
                        <div class="flex-1 space-y-2">
                            <form method="POST" action="{{ route('admin.positions.logo.store', $position) }}" enctype="multipart/form-data" class="flex flex-col gap-2">
                                @csrf
                                <label class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 cursor-pointer w-fit">
                                    <input type="file" name="company_logo" accept="image/jpeg,image/png,image/jpg,image/webp,image/svg+xml" class="sr-only" onchange="this.form.submit()" />
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" /></svg>
                                    {{ $position->company_logo_url ? 'Change Logo' : 'Upload Logo' }}
                                </label>
                                <p class="text-xs text-gray-500">JPEG, PNG, WebP, SVG. Max 2MB. Square images work best.</p>
                            </form>
                            @if ($position->company_logo_url)
                                <form method="POST" action="{{ route('admin.positions.logo.destroy', $position) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-sm text-red-600 hover:text-red-700 font-medium">Remove logo</button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <form id="delete-position-form" method="POST" action="{{ route('admin.positions.destroy', $position) }}" onsubmit="return confirm('Delete this position? This will also remove all related applications. This action cannot be undone.');" class="hidden">
            @csrf
            @method('DELETE')
        </form>
    </div>
@endsection
