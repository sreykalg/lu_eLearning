<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProgressController extends Controller
{
    public function index(Request $request): View
    {
        $courses = $request->user()->courses()->orderBy('title')->get();
        $rows = [];
        foreach ($courses as $course) {
            foreach ($course->activeEnrollments()->with('user')->get() as $e) {
                $user = $e->user;
                if (!$user->isStudent()) continue; // only show actual students
                $lessons = $course->lessons()->count();
                $done = $user->lessonProgress()->whereHas('lesson', fn ($q) => $q->where('course_id', $course->id))->where('completed', true)->count();
                $lessonPct = $lessons > 0 ? round(($done / $lessons) * 100) : 0;
                $assignments = $course->assignments()->count();
                $submitted = $user->assignmentSubmissions()->whereHas('assignment', fn ($q) => $q->where('course_id', $course->id))->count();
                $quizAttempts = $user->quizAttempts()->whereHas('quiz', fn ($q) => $q->where('course_id', $course->id))->get();
                $quizAvg = $quizAttempts->isEmpty() ? null : round($quizAttempts->avg(fn ($a) => $a->total_points > 0 ? ($a->score / $a->total_points) * 100 : 0));
                $status = $lessonPct >= 70 && ($quizAvg === null || $quizAvg >= 70) ? 'on_track' : 'at_risk';
                if ($lessonPct >= 85 && $quizAvg !== null && $quizAvg >= 85) $status = 'excellent';
                $rows[] = (object)[
                    'user' => $user,
                    'course' => $course,
                    'lesson_pct' => $lessonPct,
                    'assignments_done' => $submitted,
                    'assignments_total' => $assignments,
                    'quiz_avg' => $quizAvg,
                    'status' => $status,
                ];
            }
        }
        if ($request->course_id) {
            $rows = array_filter($rows, fn ($r) => (string) $r->course->id === (string) $request->course_id);
        }
        if ($search = trim($request->search)) {
            $search = strtolower($search);
            $rows = array_filter($rows, fn ($r) => str_contains(strtolower($r->user->name), $search));
        }
        return view('instructor.progress', compact('rows', 'courses'));
    }
}
