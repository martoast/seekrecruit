@extends('layouts.admin')

@section('title', 'Lessons')

@section('content')
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Lessons</h1>
                <p class="text-sm text-gray-500 mt-1">Create and manage learning content for candidates</p>
            </div>
            <x-ui.button variant="primary" onclick="window.location='{{ route('admin.lessons.create') }}'">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                New Lesson
            </x-ui.button>
        </div>

        @if ($lessons->isEmpty())
            <x-ui.empty-state title="No lessons yet" description="Create your first lesson to start educating candidates.">
                <x-slot:action>
                    <x-ui.button variant="primary" onclick="window.location='{{ route('admin.lessons.create') }}'">Create Lesson</x-ui.button>
                </x-slot:action>
            </x-ui.empty-state>
        @else
            <div class="space-y-3">
                @foreach ($lessons as $lesson)
                    <x-ui.card padding="md">
                        <div class="flex items-center justify-between gap-4">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-3">
                                    <p class="text-sm font-semibold text-gray-900 truncate">{{ $lesson->title }}</p>
                                    <x-ui.badge :variant="$lesson->status->value === 'published' ? 'success' : 'default'">
                                        {{ ucfirst($lesson->status->value) }}
                                    </x-ui.badge>
                                    @if ($lesson->video_url)
                                        <x-ui.badge variant="info">Video</x-ui.badge>
                                    @endif
                                </div>
                                <p class="text-xs text-gray-500 mt-1">
                                    By {{ $lesson->author->name }} · {{ $lesson->created_at->diffForHumans() }}
                                    · {{ $lesson->attachments_count ?? 0 }} attachments
                                </p>
                            </div>
                            <div class="flex items-center gap-2 flex-shrink-0">
                                @if ($lesson->status->value === 'published')
                                    <a href="{{ route('lessons.show', $lesson->slug) }}" target="_blank"
                                       class="text-xs text-primary-600 hover:underline">View live ↗</a>
                                @endif
                                <a href="{{ route('admin.lessons.edit', $lesson) }}">
                                    <x-ui.button variant="secondary" size="sm">Edit</x-ui.button>
                                </a>
                                <form method="POST" action="{{ route('admin.lessons.destroy', $lesson) }}"
                                      onsubmit="return confirm('Delete this lesson?')">
                                    @csrf
                                    @method('DELETE')
                                    <x-ui.button type="submit" variant="danger" size="sm">Delete</x-ui.button>
                                </form>
                            </div>
                        </div>
                    </x-ui.card>
                @endforeach
            </div>
        @endif
    </div>
@endsection
