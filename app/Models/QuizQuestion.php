<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'quiz_id',
        'question',
        'type',
        'options',
        'order',
        'points',
    ];

    protected $casts = [
        'options' => 'array',
    ];

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    public function getCorrectOptionIndex(): ?int
    {
        foreach ($this->options ?? [] as $index => $option) {
            if (($option['is_correct'] ?? false)) {
                return $index;
            }
        }
        return null;
    }
}
