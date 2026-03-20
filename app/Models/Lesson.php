<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Lesson extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'module_id',
        'title',
        'slug',
        'content',
        'video_url',
        'video_duration',
        'subtitle_url',
        'order',
        'is_free',
    ];

    protected $casts = [
        'is_free' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($lesson) {
            if (empty($lesson->slug)) {
                $lesson->slug = Str::slug($lesson->title);
            }
        });
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function videoQuizzes()
    {
        return $this->hasMany(VideoQuiz::class)->orderBy('timestamp_seconds');
    }

    public function attachments()
    {
        return $this->hasMany(LessonAttachment::class);
    }

    public function progresses()
    {
        return $this->hasMany(LessonProgress::class);
    }

    public function getProgressFor(User $user): ?LessonProgress
    {
        return $this->progresses()->where('user_id', $user->id)->first();
    }
}
