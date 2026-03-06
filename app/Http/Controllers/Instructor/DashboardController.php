<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $courses = $request->user()->courses()->withCount(['lessons', 'quizzes', 'assignments', 'enrollments'])->orderBy('order')->get();
        $stats = [
            'courses' => $courses->count(),
            'lessons' => $courses->sum(fn ($c) => $c->lessons_count),
            'enrollments' => $courses->sum(fn ($c) => $c->enrollments_count),
        ];
        return view('instructor.dashboard', compact('courses', 'stats'));
    }
}
