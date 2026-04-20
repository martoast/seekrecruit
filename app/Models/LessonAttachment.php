<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class LessonAttachment extends Model
{
    protected $fillable = ['lesson_id', 'original_name', 'path', 'mime_type', 'size'];

    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }

    public function getDownloadUrlAttribute(): string
    {
        return Storage::disk('public')->url($this->path);
    }

    public function getHumanSizeAttribute(): string
    {
        $bytes = $this->size;
        if ($bytes < 1024) return $bytes . ' B';
        if ($bytes < 1048576) return round($bytes / 1024, 1) . ' KB';
        return round($bytes / 1048576, 1) . ' MB';
    }
}
