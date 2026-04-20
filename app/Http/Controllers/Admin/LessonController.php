<?php

namespace App\Http\Controllers\Admin;

use App\Enums\LessonStatus;
use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Models\LessonAttachment;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LessonController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $lessons = Lesson::with('author')
            ->when(! $user->isSuperAdmin(), fn ($q) => $q->where('client_id', $user->client_id))
            ->latest()
            ->get();

        return view('admin.lessons.index', compact('lessons'));
    }

    public function create(): View
    {
        return view('admin.lessons.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $user = $request->user();

        $data = $request->validate([
            'title'     => ['required', 'string', 'max:255'],
            'body'      => ['required', 'string'],
            'video_url' => ['nullable', 'url', 'max:500'],
            'status'    => ['required', 'in:draft,published'],
        ]);

        $lesson = Lesson::create([
            ...$data,
            'slug'       => Lesson::generateSlug($data['title']),
            'client_id'  => $user->isSuperAdmin() ? null : $user->client_id,
            'created_by' => $user->id,
        ]);

        $this->handleAttachments($request, $lesson);

        return redirect()->route('admin.lessons.edit', $lesson)
            ->with('success', 'Lesson created successfully.');
    }

    public function edit(Lesson $lesson): View
    {
        $this->authorizeLesson($lesson);
        $lesson->load('attachments');
        return view('admin.lessons.edit', compact('lesson'));
    }

    public function update(Request $request, Lesson $lesson): RedirectResponse
    {
        $this->authorizeLesson($lesson);

        $data = $request->validate([
            'title'     => ['required', 'string', 'max:255'],
            'body'      => ['required', 'string'],
            'video_url' => ['nullable', 'url', 'max:500'],
            'status'    => ['required', 'in:draft,published'],
        ]);

        $lesson->update($data);

        $this->handleAttachments($request, $lesson);

        return back()->with('success', 'Lesson updated.');
    }

    public function destroy(Lesson $lesson): RedirectResponse
    {
        $this->authorizeLesson($lesson);

        foreach ($lesson->attachments as $att) {
            Storage::disk('public')->delete($att->path);
        }

        $lesson->delete();

        return redirect()->route('admin.lessons.index')
            ->with('success', 'Lesson deleted.');
    }

    public function destroyAttachment(Lesson $lesson, LessonAttachment $attachment): RedirectResponse
    {
        $this->authorizeLesson($lesson);
        abort_unless($attachment->lesson_id === $lesson->id, 404);

        Storage::disk('public')->delete($attachment->path);
        $attachment->delete();

        return back()->with('success', 'Attachment removed.');
    }

    private function handleAttachments(Request $request, Lesson $lesson): void
    {
        if (! $request->hasFile('attachments')) {
            return;
        }

        $request->validate(['attachments.*' => ['file', 'max:20480']]);

        foreach ($request->file('attachments') as $file) {
            $path = $file->store('lesson-attachments/' . $lesson->id, 'public');
            $lesson->attachments()->create([
                'original_name' => $file->getClientOriginalName(),
                'path'          => $path,
                'mime_type'     => $file->getMimeType(),
                'size'          => $file->getSize(),
            ]);
        }
    }

    private function authorizeLesson(Lesson $lesson): void
    {
        $user = auth()->user();
        if ($user->isSuperAdmin()) {
            return;
        }
        abort_unless($lesson->client_id === $user->client_id, 403);
    }
}
