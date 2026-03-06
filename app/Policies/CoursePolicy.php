<?php

namespace App\Policies;

use App\Models\Course;
use App\Models\User;

class CoursePolicy
{
    public function update(User $user, Course $course): bool
    {
        return $course->instructor_id === $user->id;
    }

    public function delete(User $user, Course $course): bool
    {
        return $course->instructor_id === $user->id;
    }
}
