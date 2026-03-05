<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Lesson;
use App\Models\LessonProgress;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class LessonController extends Controller
{
    public function show(Request $request, Course $course, Lesson $lesson): View|RedirectResponse
    {
        if ($lesson->course_id !== $course->id) {
            abort(404);
        }

        $enrollment = Enrollment::where('user_id', $request->user()->id)
            ->where('course_id', $course->id)
            ->first();

        if (!$enrollment && !$lesson->is_free) {
            return redirect()->route('courses.show', $course)
                ->with('error', 'Please enroll in the course to access this lesson.');
        }

        $course->load(['lessons']);
        $lesson->load(['videoQuizzes']);
        $progress = $lesson->getProgressFor($request->user());

        $prevLesson = $course->lessons->where('order', '<', $lesson->order)->sortByDesc('order')->first();
        $nextLesson = $course->lessons->where('order', '>', $lesson->order)->sortBy('order')->first();

        return view('lessons.show', compact('course', 'lesson', 'progress', 'prevLesson', 'nextLesson'));
    }

    public function updateProgress(Request $request)
    {
        $request->validate([
            'lesson_id' => 'required|exists:lessons,id',
            'watched_seconds' => 'required|integer|min:0',
            'completed' => 'boolean',
        ]);

        $lesson = Lesson::findOrFail($request->lesson_id);

        $progress = LessonProgress::firstOrCreate(
            [
                'user_id' => $request->user()->id,
                'lesson_id' => $lesson->id,
            ],
            ['watched_seconds' => 0, 'completed' => false]
        );

        $progress->watched_seconds = max($progress->watched_seconds, (int) $request->watched_seconds);
        if ($request->boolean('completed')) {
            $progress->completed = true;
            $progress->completed_at = $progress->completed_at ?? now();
        }
        $progress->save();

        return response()->json(['success' => true]);
    }
}
