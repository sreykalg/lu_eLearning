<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserPointEarning extends Model
{
    protected $fillable = ['user_id', 'course_id', 'source_type', 'source_id', 'points'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public static function award(User $user, string $sourceType, int $sourceId, int $points = 1, ?int $courseId = null): bool
    {
        $exists = static::where('user_id', $user->id)
            ->where('source_type', $sourceType)
            ->where('source_id', $sourceId)
            ->exists();
        if ($exists) {
            return false;
        }
        static::create([
            'user_id' => $user->id,
            'course_id' => $courseId,
            'source_type' => $sourceType,
            'source_id' => $sourceId,
            'points' => $points,
        ]);
        return true;
    }
}
