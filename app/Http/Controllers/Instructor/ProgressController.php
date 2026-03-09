<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProgressController extends Controller
{
    public function index(Request $request): View
    {
        $courses = $request->user()->courses()->with(['enrollments.user'])->get();
        $rows = [];
        foreach ($courses as $course) {
            foreach ($course->enrollments as $e) {
                $user = $e->user;
                $lessons = $course->lessons()->count();
                $done = $user->lessonProgress()->whereHas('lesson', fn ($q) => $q->where('course_id', $course->id))->where('completed', true)->count();
                $lessonPct = $lessons > 0 ? round(($done / $lessons) * 100) : 0;
                $assignments = $course->assignments()->count();
                $submitted = $user->assignmentSubmissions()->whereHas('assignment', fn ($q) => $q->where('course_id', $course->id))->count();
                $quizAvg = $user->quizAttempts()->whereHas('quiz', fn ($q) => $q->where('course_id', $course->id))->get();
                $quizAvg = $quizAvg->isEmpty() ? null : round($quizAvg->avg(fn ($a) => $a->total_points > 0 ? ($a->score / $a->total_points) * 100 : 0));
                $status = $lessonPct >= 70 && ($quizAvg === null || $quizAvg >= 70) ? 'on_track' : ($lessonPct >= 50 ? 'at_risk' : 'at_risk');
                if ($lessonPct >= 85 && $quizAvg >= 85) $status = 'excellent';
                $rows[] = (object)[
                    'user' => $user,
                    'course' => $course,
                    'lesson_pct' => $lessonPct,
                    'assignments' => "{$submitted}/{$assignments}",
                    'quiz_avg' => $quizAvg,
                    'status' => $status,
                ];
            }
        }
        return view('instructor.progress', compact('rows'));
    }
}
