<?php

namespace App\Policies;

use App\Models\Course;
use App\Models\User;

class CoursePolicy
{
    public function update(User $user, Course $course): bool
    {
        if ($course->instructor_id !== $user->id) {
            return false;
        }
        if ($course->approval_status === Course::APPROVAL_PENDING) {
            return false;
        }
        return true;
    }

    public function delete(User $user, Course $course): bool
    {
        if ($course->instructor_id !== $user->id) {
            return false;
        }
        if ($course->approval_status === Course::APPROVAL_PENDING) {
            return false;
        }
        return true;
    }
}
