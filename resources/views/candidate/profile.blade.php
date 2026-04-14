@extends('layouts.candidate')

@section('title', 'My Profile - Seek & Recruit Network')

@section('content')
    <div class="max-w-4xl mx-auto space-y-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">My Profile</h1>
            <p class="text-gray-600">Manage your personal information, photo, and CV</p>
        </div>

        {{-- Profile image uploader --}}
        <x-ui.card padding="md">
            @php
                $imageUrl = $profile?->profile_image_url;
            @endphp
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">Profile Photo</h3>
                    <x-ui.badge :variant="$imageUrl ? 'success' : 'warning'">
                        {{ $imageUrl ? 'Uploaded' : 'No Photo' }}
                    </x-ui.badge>
                </div>

                <div class="flex flex-col sm:flex-row items-center gap-6">
                    <div class="flex-shrink-0">
                        @if ($imageUrl)
                            <img src="{{ $imageUrl }}" alt="Profile photo" class="w-32 h-32 rounded-2xl object-cover border-4 border-gray-100 shadow-md" />
                        @else
                            <div class="w-32 h-32 rounded-2xl bg-gray-100 border-2 border-dashed border-gray-300 flex items-center justify-center">
                                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                        @endif
                    </div>

                    <div class="flex-1 w-full">
                        <p class="text-sm text-gray-600 mb-3">Upload a professional photo to make your profile stand out to recruiters.</p>

                        <form method="POST" action="{{ route('candidate.profile.image.store') }}" enctype="multipart/form-data" class="flex flex-col gap-3">
                            @csrf
                            <label class="cursor-pointer bg-white border-2 border-dashed border-gray-300 rounded-xl p-4 hover:border-primary-400 hover:bg-primary-50/50 transition-all">
                                <input type="file" name="profile_image" accept="image/jpeg,image/png,image/jpg,image/webp" class="sr-only" onchange="this.form.querySelector('button[type=submit]').classList.remove('hidden'); this.form.querySelector('[data-filename]').textContent = this.files[0]?.name || 'Choose an image'" />
                                <div class="flex items-center justify-center gap-3">
                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <span data-filename class="text-sm font-medium text-gray-700">Choose an image</span>
                                </div>
                            </label>
                            <x-ui.button type="submit" variant="primary" class="w-full hidden">Upload Photo</x-ui.button>
                        </form>

                        @if ($imageUrl)
                            <form method="POST" action="{{ route('candidate.profile.image.destroy') }}" class="mt-2">
                                @csrf
                                @method('DELETE')
                                <x-ui.button type="submit" variant="ghost" size="sm">Remove Photo</x-ui.button>
                            </form>
                        @endif

                        <p class="text-xs text-gray-500 mt-2">JPEG, PNG, or WebP. Max 5MB. Square images work best.</p>
                    </div>
                </div>
            </div>
        </x-ui.card>

        {{-- Profile form --}}
        <x-ui.card padding="lg">
            <h2 class="text-xl font-semibold text-gray-900 mb-6">Personal Information</h2>
            @php
                $skillsArray = old('skills') ?: (is_array($profile?->skills) ? $profile->skills : []);
                $skillsCsv = is_array($skillsArray) ? implode(',', $skillsArray) : $skillsArray;
            @endphp
            <form method="POST" action="{{ route('candidate.profile.update') }}" class="space-y-6" data-profile-form>
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <x-ui.input label="University" name="university" :value="old('university', $profile?->university)" placeholder="e.g., UABC" required />
                    <x-ui.input label="Degree" name="degree" :value="old('degree', $profile?->degree)" placeholder="e.g., Computer Science" required />
                    <x-ui.input label="Current Semester" name="semester" type="number" :value="old('semester', $profile?->semester)" placeholder="e.g., 4" />
                    <x-ui.input label="Graduation Year" name="graduation_year" type="number" :value="old('graduation_year', $profile?->graduation_year)" placeholder="e.g., 2025" />
                    <x-ui.input label="Location" name="location" :value="old('location', $profile?->location)" placeholder="e.g., Tijuana" required />
                    <x-ui.input label="Age" name="age" type="number" :value="old('age', $profile?->age)" placeholder="e.g., 22" />
                    <x-ui.select
                        label="Gender"
                        name="gender"
                        :value="old('gender', $profile?->gender?->value)"
                        :options="[
                            'male' => 'Male',
                            'female' => 'Female',
                            'other' => 'Other',
                            'prefer_not_to_say' => 'Prefer not to say',
                        ]"
                    />
                    <x-ui.input label="Phone Number" name="phone" type="tel" :value="old('phone', $profile?->phone)" placeholder="e.g., +52 664 123 4567" />
                    <x-ui.input label="LinkedIn URL" name="linkedin_url" type="url" :value="old('linkedin_url', $profile?->linkedin_url)" placeholder="https://linkedin.com/in/..." />
                </div>

                {{-- Skills --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Skills <span class="text-red-500">*</span></label>
                    <div class="flex flex-wrap gap-2 mb-3" data-skills-container>
                        @foreach ($skillsArray as $skill)
                            <span class="inline-flex items-center px-3 py-1.5 rounded-lg bg-primary-50 text-primary-700 text-sm font-medium" data-skill="{{ $skill }}">
                                {{ $skill }}
                                <button type="button" class="ml-2 hover:text-primary-900" data-remove-skill>
                                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                                </button>
                            </span>
                        @endforeach
                    </div>
                    <div class="flex gap-2">
                        <input type="text" data-skill-input placeholder="Type a skill and press Enter" class="flex-1 rounded-xl border-2 border-gray-200 px-4 py-2.5 text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500" />
                        <x-ui.button type="button" variant="secondary" data-add-skill>Add</x-ui.button>
                    </div>
                    <input type="hidden" name="skills" value="{{ $skillsCsv }}" data-skills-value />
                    @error('skills')
                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <x-ui.textarea label="Bio" name="bio" :value="old('bio', $profile?->bio)" placeholder="Tell us about yourself..." :rows="4" />

                <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                    <x-ui.button type="submit" variant="primary">Save Profile</x-ui.button>
                </div>
            </form>
        </x-ui.card>

        {{-- CV uploader --}}
        <x-ui.card padding="md">
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">CV / Resume</h3>
                    <x-ui.badge :variant="$profile?->cv_path ? 'success' : 'warning'">
                        {{ $profile?->cv_path ? 'Uploaded' : 'Not Uploaded' }}
                    </x-ui.badge>
                </div>

                @if ($profile?->cv_path)
                    <div class="bg-gray-50 rounded-xl p-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <svg class="h-10 w-10 text-primary-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/></svg>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Your CV</p>
                                    <p class="text-xs text-gray-500">PDF Document</p>
                                </div>
                            </div>
                            <div class="flex gap-2">
                                <a href="{{ route('candidate.profile.cv.download') }}" target="_blank">
                                    <x-ui.button variant="secondary" size="sm">Download</x-ui.button>
                                </a>
                                <form method="POST" action="{{ route('candidate.profile.cv.destroy') }}">
                                    @csrf
                                    @method('DELETE')
                                    <x-ui.button type="submit" variant="danger" size="sm">Delete</x-ui.button>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <form method="POST" action="{{ route('candidate.profile.cv.store') }}" enctype="multipart/form-data" class="space-y-3">
                        @csrf
                        <label class="block cursor-pointer bg-white border-2 border-dashed border-gray-300 rounded-xl p-6 hover:border-primary-400 hover:bg-primary-50/50 transition-all text-center">
                            <input type="file" name="cv" accept=".pdf" class="sr-only" onchange="this.form.querySelector('[data-cv-file]').textContent = this.files[0]?.name || 'Choose a PDF file'; this.form.querySelector('button[type=submit]').classList.remove('hidden')" />
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <p class="mt-4 text-primary-600 font-medium" data-cv-file>Choose a PDF file</p>
                            <p class="text-xs text-gray-500 mt-2">PDF format only, maximum 5 MB</p>
                        </label>
                        <x-ui.button type="submit" variant="primary" class="w-full hidden">Upload CV</x-ui.button>
                        @error('cv')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </form>
                @endif
            </div>
        </x-ui.card>
    </div>

    <script>
        (function () {
            const form = document.querySelector('[data-profile-form]');
            if (!form) return;

            const container = form.querySelector('[data-skills-container]');
            const input = form.querySelector('[data-skill-input]');
            const hidden = form.querySelector('[data-skills-value]');
            const addBtn = form.querySelector('[data-add-skill]');

            const sync = () => {
                const skills = Array.from(container.querySelectorAll('[data-skill]')).map((el) => el.dataset.skill);
                hidden.value = skills.join(',');
            };

            const addSkill = () => {
                const value = input.value.trim();
                if (!value) return;
                if (hidden.value.split(',').filter(Boolean).includes(value)) return;

                const chip = document.createElement('span');
                chip.className = 'inline-flex items-center px-3 py-1.5 rounded-lg bg-primary-50 text-primary-700 text-sm font-medium';
                chip.dataset.skill = value;
                chip.innerHTML = value + ' <button type="button" class="ml-2 hover:text-primary-900" data-remove-skill><svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg></button>';
                container.appendChild(chip);
                input.value = '';
                sync();
            };

            addBtn.addEventListener('click', addSkill);
            input.addEventListener('keydown', (e) => {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    addSkill();
                }
            });

            container.addEventListener('click', (e) => {
                const btn = e.target.closest('[data-remove-skill]');
                if (!btn) return;
                btn.closest('[data-skill]').remove();
                sync();
            });
        })();
    </script>
@endsection
