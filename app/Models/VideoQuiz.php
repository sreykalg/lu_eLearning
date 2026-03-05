<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoQuiz extends Model
{
    use HasFactory;

    protected $fillable = [
        'lesson_id',
        'timestamp_seconds',
        'question',
        'options',
        'order',
    ];

    protected $casts = [
        'options' => 'array',
    ];

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }
}
