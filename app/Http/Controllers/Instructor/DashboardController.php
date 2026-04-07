<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\LessonProgress;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $courses = $request->user()->courses()->withCount(['lessons', 'quizzes', 'assignments', 'activeEnrollments as enrollments_count'])->orderBy('order')->get();

        $courses->each(function ($course) {
            $course->completion_pct = 0;
            $total = $course->enrollments_count * max(1, $course->lessons_count);
            if ($total > 0) {
                $done = LessonProgress::where('completed', true)
                    ->whereHas('lesson', fn ($q) => $q->where('course_id', $course->id))
                    ->count();
                $course->completion_pct = (int) round(min(100, ($done / $total) * 100));
            }
        });

        $avgCompletion = $courses->isNotEmpty()
            ? (int) round($courses->avg(fn ($c) => $c->completion_pct))
            : 0;

        $stats = [
            'courses' => $courses->count(),
            'lessons' => $courses->sum(fn ($c) => $c->lessons_count),
            'enrollments' => $courses->sum(fn ($c) => $c->enrollments_count),
            'avg_completion' => $avgCompletion,
        ];

        return view('instructor.dashboard', compact('courses', 'stats'));
    }
}
