<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LessonCommentLike extends Model
{
    protected $fillable = ['lesson_comment_id', 'user_id'];

    public function comment(): BelongsTo
    {
        return $this->belongsTo(LessonComment::class, 'lesson_comment_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
