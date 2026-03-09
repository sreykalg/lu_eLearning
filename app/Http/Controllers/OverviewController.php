<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OverviewController extends Controller
{
    public function index(Request $request): View
    {
        $courses = Course::with('instructor')
            ->where('is_published', true)
            ->orderBy('order')
            ->paginate(12);

        $enrolledIds = collect();
        if ($request->user()) {
            $enrolledIds = $request->user()->enrollments()->pluck('course_id');
        }

        $layout = auth()->user()->isStudent()
            ? 'layouts.student-inner'
            : (auth()->user()->isHeadOfDept() ? 'layouts.hod-inner' : 'layouts.instructor-inner');

        return view('overview.index', compact('courses', 'enrolledIds', 'layout'));
    }
}
