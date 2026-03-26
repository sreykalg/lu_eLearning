<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'instructor_id',
        'title',
        'slug',
        'description',
        'thumbnail',
        'level',
        'quiz_weight',
        'assignment_weight',
        'midterm_weight',
        'final_weight',
        'order',
        'is_published',
        'approval_status',
        'submitted_at',
        'approved_at',
        'revision_notes',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'submitted_at' => 'datetime',
        'approved_at' => 'datetime',
    ];

    public const APPROVAL_DRAFT = 'draft';
    public const APPROVAL_PENDING = 'pending';
    public const APPROVAL_APPROVED = 'approved';
    public const APPROVAL_NEEDS_REVISION = 'needs_revision';

    public function isPending(): bool
    {
        return $this->approval_status === self::APPROVAL_PENDING;
    }

    public function isApproved(): bool
    {
        return $this->approval_status === self::APPROVAL_APPROVED;
    }

    public function isNeedsRevision(): bool
    {
        return $this->approval_status === self::APPROVAL_NEEDS_REVISION;
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($course) {
            if (empty($course->slug)) {
                $course->slug = Str::slug($course->title);
            }
        });
    }

    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function modules()
    {
        return $this->hasMany(Module::class)->orderBy('order');
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class)->orderBy('order');
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function quizzes()
    {
        return $this->hasMany(Quiz::class)->orderBy('order');
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class)->orderBy('order');
    }

    public function discussions()
    {
        return $this->hasMany(Discussion::class);
    }

    public function announcements()
    {
        return $this->hasMany(Announcement::class)->latest();
    }
}
