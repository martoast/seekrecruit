<?php

namespace App\Http\Controllers;

use App\Enums\LessonStatus;
use App\Models\Lesson;
use App\Models\LessonComment;
use App\Models\LessonCommentLike;
use App\Models\LessonCompletion;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LessonController extends Controller
{
    public function index(): View
    {
        $lessons = Lesson::where('status', LessonStatus::PUBLISHED)
            ->withCount('completions')
            ->latest()
            ->get();

        $completedIds = [];
        if ($user = auth()->user()) {
            $completedIds = LessonCompletion::where('user_id', $user->id)
                ->pluck('lesson_id')
                ->toArray();
        }

        return view('lessons.index', compact('lessons', 'completedIds'));
    }

    public function show(string $slug): View
    {
        $lesson = Lesson::where('slug', $slug)
            ->where('status', LessonStatus::PUBLISHED)
            ->with(['attachments', 'comments.user', 'comments.replies.user', 'comments.likes'])
            ->firstOrFail();

        $user = auth()->user();
        $completed = $user ? $lesson->isCompletedBy($user) : false;

        $likedCommentIds = [];
        if ($user) {
            $allCommentIds = $lesson->comments->pluck('id')
                ->merge($lesson->comments->flatMap->replies->pluck('id'))
                ->toArray();
            $likedCommentIds = LessonCommentLike::where('user_id', $user->id)
                ->whereIn('lesson_comment_id', $allCommentIds)
                ->pluck('lesson_comment_id')
                ->toArray();
        }

        return view('lessons.show', compact('lesson', 'completed', 'likedCommentIds'));
    }

    public function complete(Request $request, string $slug): RedirectResponse
    {
        $lesson = Lesson::where('slug', $slug)->where('status', LessonStatus::PUBLISHED)->firstOrFail();

        LessonCompletion::firstOrCreate([
            'lesson_id' => $lesson->id,
            'user_id'   => $request->user()->id,
        ]);

        return back()->with('success', 'Lesson marked as complete! You earned a badge.');
    }

    public function storeComment(Request $request, string $slug): RedirectResponse
    {
        $lesson = Lesson::where('slug', $slug)->where('status', LessonStatus::PUBLISHED)->firstOrFail();

        $data = $request->validate([
            'body'      => ['required', 'string', 'max:2000'],
            'parent_id' => ['nullable', 'exists:lesson_comments,id'],
        ]);

        // Ensure reply parent belongs to this lesson and is a top-level comment
        if ($data['parent_id'] ?? null) {
            $parent = LessonComment::findOrFail($data['parent_id']);
            abort_unless($parent->lesson_id === $lesson->id && is_null($parent->parent_id), 422);
        }

        LessonComment::create([
            'lesson_id' => $lesson->id,
            'user_id'   => $request->user()->id,
            'parent_id' => $data['parent_id'] ?? null,
            'body'      => $data['body'],
        ]);

        return back()->with('success', 'Comment posted.');
    }

    public function toggleLike(Request $request, LessonComment $comment): RedirectResponse
    {
        $user = $request->user();

        $existing = LessonCommentLike::where('lesson_comment_id', $comment->id)
            ->where('user_id', $user->id)
            ->first();

        if ($existing) {
            $existing->delete();
            $comment->decrement('likes_count');
        } else {
            LessonCommentLike::create([
                'lesson_comment_id' => $comment->id,
                'user_id'           => $user->id,
            ]);
            $comment->increment('likes_count');
        }

        return back();
    }

    public function destroyComment(Request $request, LessonComment $comment): RedirectResponse
    {
        $user = $request->user();
        abort_unless($comment->user_id === $user->id || $user->isAdmin(), 403);

        $comment->delete();

        return back()->with('success', 'Comment deleted.');
    }
}
