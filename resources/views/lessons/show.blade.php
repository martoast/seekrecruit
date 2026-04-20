@extends('layouts.app')

@section('title', $lesson->title . ' - Seek & Recruit')

@section('content')
    <div class="max-w-4xl mx-auto px-4 py-10 space-y-10">

        {{-- Header --}}
        <div class="space-y-3">
            <a href="{{ route('lessons.index') }}" class="text-sm text-primary-600 hover:underline">← All Lessons</a>
            <div class="flex items-start justify-between gap-4">
                <h1 class="text-3xl font-bold text-gray-900">{{ $lesson->title }}</h1>
                @if ($completed)
                    <span class="inline-flex items-center gap-1.5 bg-green-100 text-green-700 text-sm font-semibold px-3 py-1.5 rounded-full flex-shrink-0">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        Badge Earned
                    </span>
                @endif
            </div>
        </div>

        {{-- Video --}}
        @if ($lesson->embed_url)
            <div class="rounded-2xl overflow-hidden shadow-lg bg-black aspect-video">
                <iframe src="{{ $lesson->embed_url }}"
                    class="w-full h-full"
                    frameborder="0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen>
                </iframe>
            </div>
        @endif

        {{-- Lesson body --}}
        <div class="prose prose-gray max-w-none">
            {!! nl2br(e($lesson->body)) !!}
        </div>

        {{-- Attachments --}}
        @if ($lesson->attachments->isNotEmpty())
            <div>
                <h2 class="text-lg font-semibold text-gray-900 mb-3">Downloads</h2>
                <div class="space-y-2">
                    @foreach ($lesson->attachments as $attachment)
                        <a href="{{ $attachment->download_url }}" download="{{ $attachment->original_name }}"
                           class="flex items-center gap-3 bg-gray-50 hover:bg-gray-100 rounded-xl px-4 py-3 transition-colors group">
                            <svg class="w-5 h-5 text-primary-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"/></svg>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate group-hover:text-primary-600">{{ $attachment->original_name }}</p>
                                <p class="text-xs text-gray-500">{{ $attachment->human_size }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Mark complete --}}
        @auth
            @if (auth()->user()->isCandidate() && ! $completed)
                <form method="POST" action="{{ route('lessons.complete', $lesson->slug) }}">
                    @csrf
                    <x-ui.button type="submit" variant="primary" size="lg" class="w-full">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Mark as Complete & Earn Badge
                    </x-ui.button>
                </form>
            @endif
        @else
            <div class="bg-primary-50 border border-primary-200 rounded-2xl p-6 text-center">
                <p class="text-gray-700 mb-3">Sign in to track your progress and earn a badge for completing this lesson.</p>
                <a href="{{ route('login') }}"><x-ui.button variant="primary">Sign In</x-ui.button></a>
            </div>
        @endauth

        {{-- Comments --}}
        <div id="comments" class="space-y-6">
            <h2 class="text-xl font-bold text-gray-900">Comments</h2>

            @auth
                <x-ui.card padding="md">
                    <form method="POST" action="{{ route('lessons.comments.store', $lesson->slug) }}" class="space-y-3">
                        @csrf
                        <input type="hidden" name="parent_id" value="">
                        <div>
                            <textarea name="body" rows="3" placeholder="Share your thoughts or questions..."
                                class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 resize-none"
                                required></textarea>
                        </div>
                        <div class="flex justify-end">
                            <x-ui.button type="submit" variant="primary" size="sm">Post Comment</x-ui.button>
                        </div>
                    </form>
                </x-ui.card>
            @endauth

            @if ($lesson->comments->isEmpty())
                <p class="text-sm text-gray-500 text-center py-6">No comments yet. Be the first!</p>
            @else
                <div class="space-y-4">
                    @foreach ($lesson->comments as $comment)
                        <div class="space-y-3">
                            {{-- Top-level comment --}}
                            <x-ui.card padding="md">
                                <div class="space-y-3">
                                    <div class="flex items-start justify-between gap-3">
                                        <div class="flex items-center gap-2">
                                            <div class="w-8 h-8 rounded-full bg-primary-100 flex items-center justify-center flex-shrink-0">
                                                <span class="text-primary-700 text-xs font-bold">{{ mb_substr($comment->user->name, 0, 1) }}</span>
                                            </div>
                                            <div>
                                                <p class="text-sm font-semibold text-gray-900">{{ $comment->user->name }}</p>
                                                <p class="text-xs text-gray-400">{{ $comment->created_at->diffForHumans() }}</p>
                                            </div>
                                        </div>
                                        @auth
                                            @if (auth()->id() === $comment->user_id || auth()->user()->isAdmin())
                                                <form method="POST" action="{{ route('lessons.comments.destroy', $comment) }}"
                                                      onsubmit="return confirm('Delete comment?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-xs text-gray-400 hover:text-red-500">Delete</button>
                                                </form>
                                            @endif
                                        @endauth
                                    </div>
                                    <p class="text-sm text-gray-700">{{ $comment->body }}</p>
                                    <div class="flex items-center gap-4">
                                        @auth
                                            <form method="POST" action="{{ route('lessons.comments.like', $comment) }}">
                                                @csrf
                                                <button type="submit"
                                                    class="flex items-center gap-1.5 text-xs {{ in_array($comment->id, $likedCommentIds) ? 'text-primary-600 font-semibold' : 'text-gray-400 hover:text-primary-500' }} transition-colors">
                                                    <svg class="w-4 h-4" fill="{{ in_array($comment->id, $likedCommentIds) ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"/>
                                                    </svg>
                                                    {{ $comment->likes_count }}
                                                </button>
                                            </form>
                                            <button type="button" onclick="toggleReplyForm({{ $comment->id }})"
                                                class="text-xs text-gray-400 hover:text-primary-500 transition-colors">Reply</button>
                                        @else
                                            <span class="flex items-center gap-1.5 text-xs text-gray-400">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"/></svg>
                                                {{ $comment->likes_count }}
                                            </span>
                                        @endauth
                                    </div>

                                    {{-- Reply form (hidden by default) --}}
                                    @auth
                                        <div id="reply-form-{{ $comment->id }}" class="hidden mt-2">
                                            <form method="POST" action="{{ route('lessons.comments.store', $lesson->slug) }}" class="space-y-2">
                                                @csrf
                                                <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                                                <textarea name="body" rows="2" placeholder="Write a reply..."
                                                    class="w-full rounded-xl border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 resize-none"
                                                    required></textarea>
                                                <div class="flex gap-2 justify-end">
                                                    <button type="button" onclick="toggleReplyForm({{ $comment->id }})"
                                                        class="text-xs text-gray-500 hover:text-gray-700">Cancel</button>
                                                    <x-ui.button type="submit" variant="primary" size="sm">Reply</x-ui.button>
                                                </div>
                                            </form>
                                        </div>
                                    @endauth
                                </div>
                            </x-ui.card>

                            {{-- Replies --}}
                            @if ($comment->replies->isNotEmpty())
                                <div class="ml-8 space-y-2">
                                    @foreach ($comment->replies as $reply)
                                        <x-ui.card padding="md">
                                            <div class="space-y-2">
                                                <div class="flex items-start justify-between gap-3">
                                                    <div class="flex items-center gap-2">
                                                        <div class="w-7 h-7 rounded-full bg-gray-100 flex items-center justify-center flex-shrink-0">
                                                            <span class="text-gray-600 text-xs font-bold">{{ mb_substr($reply->user->name, 0, 1) }}</span>
                                                        </div>
                                                        <div>
                                                            <p class="text-sm font-semibold text-gray-900">{{ $reply->user->name }}</p>
                                                            <p class="text-xs text-gray-400">{{ $reply->created_at->diffForHumans() }}</p>
                                                        </div>
                                                    </div>
                                                    @auth
                                                        @if (auth()->id() === $reply->user_id || auth()->user()->isAdmin())
                                                            <form method="POST" action="{{ route('lessons.comments.destroy', $reply) }}"
                                                                  onsubmit="return confirm('Delete reply?')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="text-xs text-gray-400 hover:text-red-500">Delete</button>
                                                            </form>
                                                        @endif
                                                    @endauth
                                                </div>
                                                <p class="text-sm text-gray-700">{{ $reply->body }}</p>
                                                <div class="flex items-center gap-3">
                                                    @auth
                                                        <form method="POST" action="{{ route('lessons.comments.like', $reply) }}">
                                                            @csrf
                                                            <button type="submit"
                                                                class="flex items-center gap-1.5 text-xs {{ in_array($reply->id, $likedCommentIds) ? 'text-primary-600 font-semibold' : 'text-gray-400 hover:text-primary-500' }} transition-colors">
                                                                <svg class="w-3.5 h-3.5" fill="{{ in_array($reply->id, $likedCommentIds) ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"/>
                                                                </svg>
                                                                {{ $reply->likes_count }}
                                                            </button>
                                                        </form>
                                                    @else
                                                        <span class="text-xs text-gray-400">{{ $reply->likes_count }} likes</span>
                                                    @endauth
                                                </div>
                                            </div>
                                        </x-ui.card>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <script>
        function toggleReplyForm(commentId) {
            const form = document.getElementById('reply-form-' + commentId);
            form.classList.toggle('hidden');
            if (!form.classList.contains('hidden')) {
                form.querySelector('textarea').focus();
            }
        }
    </script>
@endsection
