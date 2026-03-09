<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CourseController extends Controller
{
    public function index(Request $request): View
    {
        $query = $request->user()->enrolledCourses()->with('instructor')->withCount('lessons');
        if ($request->filled('level')) {
            $query->where('level', $request->level);
        }
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }
        $courses = $query->orderBy('title')->get();
        $courses->each(function ($c) use ($request) {
            $lessons = $c->lessons()->count();
            $done = $request->user()->lessonProgress()->whereHas('lesson', fn ($q) => $q->where('course_id', $c->id))->where('completed', true)->count();
            $c->progress_pct = $lessons > 0 ? round(($done / $lessons) * 100) : 0;
            $c->progress_done = $done;
            $c->course_points = $request->user()->coursePoints($c->id);
        });
        $levels = ['beginner' => 'Beginner', 'intermediate' => 'Intermediate', 'advanced' => 'Advanced'];
        $totalPoints = $request->user()->totalPoints();
        return view('student.courses', compact('courses', 'levels', 'totalPoints'));
    }
}
