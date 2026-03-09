<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'lesson_id',
        'title',
        'instructions',
        'order',
        'max_score',
        'grading_type',
        'due_at',
        'allow_late_submission',
        'is_required',
    ];

    protected $casts = [
        'due_at' => 'datetime',
        'allow_late_submission' => 'boolean',
        'is_required' => 'boolean',
    ];

    public function canSubmit(): bool
    {
        if ($this->due_at === null) {
            return true;
        }
        if (now()->lte($this->due_at)) {
            return true;
        }
        return (bool) ($this->allow_late_submission ?? false);
    }

    public function isPastDue(): bool
    {
        return $this->due_at && now()->gt($this->due_at);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    public function submissions()
    {
        return $this->hasMany(AssignmentSubmission::class, 'assignment_id');
    }
}
