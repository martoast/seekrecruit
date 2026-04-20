@extends('layouts.admin')

@section('title', 'Edit Lesson')

@section('content')
    <div class="max-w-3xl mx-auto space-y-6">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.lessons.index') }}" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <h1 class="text-2xl font-bold text-gray-900">Edit Lesson</h1>
            @if ($lesson->status->value === 'published')
                <a href="{{ route('lessons.show', $lesson->slug) }}" target="_blank"
                   class="text-sm text-primary-600 hover:underline ml-auto">View live ↗</a>
            @endif
        </div>

        <x-ui.card padding="lg">
            <form method="POST" action="{{ route('admin.lessons.update', $lesson) }}" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <x-ui.input label="Title" name="title" required :value="old('title', $lesson->title)" />

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Body</label>
                    <textarea name="body" rows="10"
                        class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                        required>{{ old('body', $lesson->body) }}</textarea>
                    @error('body')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                <x-ui.input
                    label="Video URL (YouTube or Loom)"
                    name="video_url"
                    type="url"
                    placeholder="https://www.youtube.com/watch?v=..."
                    :value="old('video_url', $lesson->video_url)"
                />

                {{-- Existing attachments --}}
                @if ($lesson->attachments->isNotEmpty())
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Current Attachments</label>
                        <div class="space-y-2">
                            @foreach ($lesson->attachments as $attachment)
                                <div class="flex items-center justify-between bg-gray-50 rounded-lg px-4 py-2">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                                        <span class="text-sm text-gray-700 truncate">{{ $attachment->original_name }}</span>
                                        <span class="text-xs text-gray-400">{{ $attachment->human_size }}</span>
                                    </div>
                                    <form method="POST" action="{{ route('admin.lessons.attachments.destroy', [$lesson, $attachment]) }}"
                                          onsubmit="return confirm('Remove this attachment?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700 text-xs ml-4">Remove</button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Add More Attachments</label>
                    <input type="file" name="attachments[]" multiple
                        class="w-full text-sm text-gray-700 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100" />
                    <p class="text-xs text-gray-500 mt-1">Max 20MB each.</p>
                </div>

                <x-ui.select
                    label="Status"
                    name="status"
                    :options="['draft' => 'Draft', 'published' => 'Published']"
                    :value="old('status', $lesson->status->value)"
                />

                <div class="flex justify-end gap-3">
                    <a href="{{ route('admin.lessons.index') }}">
                        <x-ui.button type="button" variant="ghost">Cancel</x-ui.button>
                    </a>
                    <x-ui.button type="submit" variant="primary">Save Changes</x-ui.button>
                </div>
            </form>
        </x-ui.card>
    </div>
@endsection
