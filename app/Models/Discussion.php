<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discussion extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'course_id',
        'lesson_id',
        'title',
        'body',
        'is_pinned',
        'is_resolved',
        'resolved_by',
    ];

    protected $casts = [
        'is_pinned' => 'boolean',
        'is_resolved' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    public function replies()
    {
        return $this->hasMany(DiscussionReply::class, 'discussion_id')->whereNull('parent_id');
    }

    public function allReplies()
    {
        return $this->hasMany(DiscussionReply::class, 'discussion_id');
    }
}
