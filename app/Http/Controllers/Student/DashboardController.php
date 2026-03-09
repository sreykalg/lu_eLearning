<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $enrollments = $user->enrollments()->with('course')->get();
        $totalProgress = 0;
        $count = 0;
        foreach ($enrollments as $e) {
            $lessons = $e->course->lessons()->count();
            if ($lessons > 0) {
                $done = $user->lessonProgress()->whereHas('lesson', fn ($q) => $q->where('course_id', $e->course_id))->where('completed', true)->count();
                $totalProgress += ($done / $lessons) * 100;
                $count++;
            }
        }
        $avgProgress = $count > 0 ? round($totalProgress / $count, 0) : 0;
        return view('student.dashboard', compact('enrollments', 'avgProgress'));
    }
}
