<?php

namespace App\Models;

use App\Enums\LessonStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lesson extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'body',
        'video_url',
        'client_id',
        'created_by',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'status' => LessonStatus::class,
        ];
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(LessonAttachment::class);
    }

    public function completions(): HasMany
    {
        return $this->hasMany(LessonCompletion::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(LessonComment::class)->whereNull('parent_id')->orderByDesc('likes_count')->orderBy('created_at');
    }

    public function getEmbedUrlAttribute(): ?string
    {
        if (! $this->video_url) {
            return null;
        }

        if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]+)/', $this->video_url, $m)) {
            return 'https://www.youtube.com/embed/' . $m[1];
        }

        if (preg_match('/loom\.com\/share\/([a-zA-Z0-9]+)/', $this->video_url, $m)) {
            return 'https://www.loom.com/embed/' . $m[1];
        }

        return $this->video_url;
    }

    public function isCompletedBy(User $user): bool
    {
        return $this->completions()->where('user_id', $user->id)->exists();
    }

    public static function generateSlug(string $title): string
    {
        $slug = \Str::slug($title);
        $count = static::where('slug', 'like', $slug . '%')->count();
        return $count ? $slug . '-' . ($count + 1) : $slug;
    }
}
