<?php

namespace App\Http\Controllers\Hod;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ApprovalController extends Controller
{
    public function index(Request $request): View
    {
        $query = Course::with('instructor')->withCount('lessons');
        if ($request->filled('status')) {
            if ($request->status === 'published') $query->where('is_published', true);
            elseif ($request->status === 'draft') $query->where('is_published', false);
        }
        $courses = $query->orderBy('updated_at', 'desc')->get();
        return view('hod.approval', compact('courses'));
    }

    public function approve(Course $course)
    {
        $course->update(['is_published' => true]);
        return back()->with('success', 'Course approved.');
    }
}
