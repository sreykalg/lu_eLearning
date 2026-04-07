<?php

namespace App\Http\Controllers\Hod;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'courses' => Course::count(),
            'students' => User::where('role', 'student')->count(),
            'instructors' => User::where('role', 'instructor')->count(),
            'pending' => Course::where('is_published', false)->count(),
        ];
        $instructors = User::where('role', 'instructor')->withCount('courses')->get();
        $completion = Course::where('is_published', true)->withCount(['lessons', 'activeEnrollments as enrollments_count'])->take(5)->get();
        $completion->each(function ($c) {
            $c->completion_pct = 0;
            $total = $c->enrollments_count * max(1, $c->lessons_count);
            if ($total > 0) {
                $done = \App\Models\LessonProgress::where('completed', true)->whereHas('lesson', fn ($q) => $q->where('course_id', $c->id))->count();
                $c->completion_pct = (int) round(($done / $total) * 100);
            }
        });
        return view('hod.dashboard', compact('stats', 'instructors', 'completion'));
    }
}
