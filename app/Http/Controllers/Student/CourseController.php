<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CourseController extends Controller
{
    public function index(Request $request): View
    {
        $courses = $request->user()->enrolledCourses()->with('instructor')->withCount('lessons')->get();
        $courses->each(function ($c) use ($request) {
            $lessons = $c->lessons()->count();
            $done = $request->user()->lessonProgress()->whereHas('lesson', fn ($q) => $q->where('course_id', $c->id))->where('completed', true)->count();
            $c->progress_pct = $lessons > 0 ? round(($done / $lessons) * 100) : 0;
            $c->progress_done = $done;
        });
        return view('student.courses', compact('courses'));
    }
}
