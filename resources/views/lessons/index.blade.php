@extends('layouts.app')

@section('title', 'Lessons - Seek & Recruit')

@section('content')
    <div class="max-w-5xl mx-auto px-4 py-10 space-y-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Lessons</h1>
            <p class="text-gray-600 mt-1">Learn new skills and earn badges for your profile</p>
        </div>

        @if ($lessons->isEmpty())
            <x-ui.empty-state title="No lessons yet" description="Check back soon — new content is on the way." />
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach ($lessons as $lesson)
                    @php $done = in_array($lesson->id, $completedIds); @endphp
                    <a href="{{ route('lessons.show', $lesson->slug) }}" class="group block">
                        <x-ui.card padding="lg">
                            <div class="space-y-3">
                                <div class="flex items-start justify-between gap-3">
                                    <h2 class="text-lg font-semibold text-gray-900 group-hover:text-primary-600 transition-colors">{{ $lesson->title }}</h2>
                                    @if ($done)
                                        <span class="inline-flex items-center gap-1 bg-green-100 text-green-700 text-xs font-semibold px-2.5 py-1 rounded-full flex-shrink-0">
                                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                            Completed
                                        </span>
                                    @endif
                                </div>
                                <p class="text-sm text-gray-600 line-clamp-2">{{ Str::limit(strip_tags($lesson->body), 120) }}</p>
                                <div class="flex items-center gap-4 text-xs text-gray-500">
                                    @if ($lesson->video_url)
                                        <span class="flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            Video included
                                        </span>
                                    @endif
                                    <span>{{ $lesson->completions_count }} {{ Str::plural('completion', $lesson->completions_count) }}</span>
                                </div>
                            </div>
                        </x-ui.card>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
@endsection
