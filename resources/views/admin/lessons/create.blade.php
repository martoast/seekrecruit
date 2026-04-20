@extends('layouts.admin')

@section('title', 'Create Lesson')

@section('content')
    <div class="max-w-3xl mx-auto space-y-6">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.lessons.index') }}" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <h1 class="text-2xl font-bold text-gray-900">Create Lesson</h1>
        </div>

        <x-ui.card padding="lg">
            <form method="POST" action="{{ route('admin.lessons.store') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <x-ui.input label="Title" name="title" required :value="old('title')" />

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Body</label>
                    <textarea name="body" rows="10"
                        class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                        placeholder="Write your lesson content here..."
                        required>{{ old('body') }}</textarea>
                    @error('body')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                <x-ui.input
                    label="Video URL (YouTube or Loom)"
                    name="video_url"
                    type="url"
                    placeholder="https://www.youtube.com/watch?v=..."
                    :value="old('video_url')"
                />

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Attachments</label>
                    <input type="file" name="attachments[]" multiple
                        class="w-full text-sm text-gray-700 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100" />
                    <p class="text-xs text-gray-500 mt-1">You can upload multiple files. Max 20MB each.</p>
                </div>

                <x-ui.select
                    label="Status"
                    name="status"
                    :options="['draft' => 'Draft', 'published' => 'Published']"
                    :value="old('status', 'draft')"
                />

                <div class="flex justify-end gap-3">
                    <a href="{{ route('admin.lessons.index') }}">
                        <x-ui.button type="button" variant="ghost">Cancel</x-ui.button>
                    </a>
                    <x-ui.button type="submit" variant="primary">Create Lesson</x-ui.button>
                </div>
            </form>
        </x-ui.card>
    </div>
@endsection
