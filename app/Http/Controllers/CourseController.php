<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CourseController extends Controller
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

        return view('courses.index', compact('courses', 'enrolledIds'));
    }

    public function show(Request $request, Course $course): View
    {
        $canPreview = $request->user() && (
            ($request->user()->isInstructor() && $course->instructor_id === $request->user()->id) ||
            $request->user()->isHeadOfDept()
        );
        if (!$course->is_published && !$canPreview) {
            abort(404);
        }

        $course->load(['lessons', 'quizzes', 'assignments', 'instructor']);

        $enrollment = null;
        $progress = collect();
        $coursePoints = 0;
        if ($request->user()) {
            $enrollment = Enrollment::where('user_id', $request->user()->id)
                ->where('course_id', $course->id)
                ->first();
            if ($enrollment) {
                $progress = $request->user()->lessonProgress()
                    ->whereIn('lesson_id', $course->lessons->pluck('id'))
                    ->get()
                    ->keyBy('lesson_id');
                $coursePoints = $request->user()->coursePoints($course->id);
            }
        }

        return view('courses.show', compact('course', 'enrollment', 'progress', 'coursePoints'));
    }

    public function enroll(Request $request, Course $course)
    {
        $exists = Enrollment::where('user_id', $request->user()->id)
            ->where('course_id', $course->id)
            ->exists();

        if (!$exists) {
            Enrollment::create([
                'user_id' => $request->user()->id,
                'course_id' => $course->id,
            ]);
        }

        return redirect()->route('courses.show', $course)
            ->with('success', 'Successfully enrolled!');
    }
}
