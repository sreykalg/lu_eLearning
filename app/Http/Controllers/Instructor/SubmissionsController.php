<?php

namespace App\Http\Controllers\Instructor;

use App\Models\Course;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SubmissionsController extends Controller
{
    public function index(Request $request): View
    {
        $courses = $request->user()->courses()
            ->withCount(['assignments', 'quizzes'])
            ->with(['assignments' => fn ($q) => $q->withCount('submissions'), 'quizzes' => fn ($q) => $q->withCount('attempts')])
            ->orderByRaw('(assignments_count + quizzes_count) desc')
            ->orderBy('title')
            ->get();

        return view('instructor.submissions.index', compact('courses'));
    }

    public function show(Request $request, Course $course): View
    {
        abort_unless(
            $request->user()->courses()->whereKey($course->id)->exists(),
            404
        );

        $course->load([
            'assignments' => fn ($q) => $q->withCount('submissions')->orderBy('title'),
            'quizzes' => fn ($q) => $q->withCount('attempts')->orderBy('title'),
        ]);

        return view('instructor.submissions.show', compact('course'));
    }
}
