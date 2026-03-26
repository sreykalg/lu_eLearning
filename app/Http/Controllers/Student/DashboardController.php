<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Assignment;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $courseIds = $user->enrollments()->pluck('course_id');
        $enrollments = $user->enrollments()->with(['course.instructor'])->get();

        $totalProgress = 0;
        $count = 0;
        foreach ($enrollments as $e) {
            $lessons = $e->course->lessons()->count();
            if ($lessons > 0) {
                $done = $user->lessonProgress()->whereHas('lesson', fn ($q) => $q->where('course_id', $e->course_id))->where('completed', true)->count();
                $pct = ($done / $lessons) * 100;
                $e->progress_pct = round($pct);
                $e->progress_done = $done;
                $e->progress_total = $lessons;
                $totalProgress += $pct;
                $count++;
            } else {
                $e->progress_pct = 0;
                $e->progress_done = 0;
                $e->progress_total = 0;
            }
        }
        $avgProgress = $count > 0 ? round($totalProgress / $count, 0) : 0;

        $continueCourse = $enrollments->filter(fn ($e) => ($e->progress_pct ?? 0) < 100)->first() ?? $enrollments->first();

        $deadlines = Assignment::whereIn('course_id', $courseIds)
            ->whereNotNull('due_at')
            ->where('due_at', '>=', now())
            ->with('course')
            ->orderBy('due_at')
            ->limit(6)
            ->get();

        $totalPoints = $user->totalPoints();

        $announcements = Announcement::whereIn('course_id', $courseIds)
            ->where(function ($q) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
            })
            ->with(['course', 'instructor'])
            ->latest()
            ->limit(10)
            ->get();

        return view('student.dashboard', compact('enrollments', 'avgProgress', 'continueCourse', 'deadlines', 'totalPoints', 'announcements'));
    }
}
