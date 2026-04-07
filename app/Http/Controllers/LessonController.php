<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Lesson;
use App\Models\LessonAttachment;
use App\Models\LessonProgress;
use App\Models\UserPointEarning;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class LessonController extends Controller
{
    public function show(Request $request, Course $course, Lesson $lesson): View|RedirectResponse
    {
        if ($lesson->course_id !== $course->id) {
            abort(404);
        }

        $enrollment = Enrollment::query()
            ->where('user_id', $request->user()->id)
            ->where('course_id', $course->id)
            ->active()
            ->first();

        if (!$enrollment && !$lesson->is_free) {
            return redirect()->route('courses.show', $course)
                ->with('error', 'Please enroll in the course to access this lesson.');
        }

        $course->load(['lessons']);
        if ($enrollment && !$request->user()->isInstructor() && !$request->user()->isHeadOfDept()) {
            $prevLesson = $course->lessons->where('order', '<', $lesson->order)->sortByDesc('order')->first();
            if ($prevLesson) {
                $prevProgress = $prevLesson->getProgressFor($request->user());
                if (!($prevProgress && $prevProgress->completed)) {
                    return redirect()->route('courses.show', $course)
                        ->with('error', 'Complete the previous lesson to unlock this one.');
                }
            }
        }
        $lesson->load(['videoQuizzes', 'attachments']);
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
        $justCompleted = false;
        if ($request->boolean('completed')) {
            if (!$progress->completed) {
                $justCompleted = true;
            }
            $progress->completed = true;
            $progress->completed_at = $progress->completed_at ?? now();
        }
        $progress->save();

        if ($justCompleted) {
            UserPointEarning::award($request->user(), 'lesson', $lesson->id, 1, $lesson->course_id);
        }

        return response()->json(['success' => true]);
    }

    public function downloadAttachment(Request $request, LessonAttachment $attachment): BinaryFileResponse|RedirectResponse
    {
        $attachment->load('lesson');
        $lesson = $attachment->lesson;
        if (!$lesson) {
            abort(404);
        }
        $course = $lesson->course;
        $enrollment = Enrollment::query()
            ->where('user_id', $request->user()->id)
            ->where('course_id', $course->id)
            ->active()
            ->first();
        if (!$enrollment && !$lesson->is_free) {
            return redirect()->route('courses.show', $course)
                ->with('error', 'Please enroll in the course to access this file.');
        }
        $relativePath = Str::after($attachment->path, '/storage/');
        $fullPath = storage_path('app/public/' . $relativePath);
        if (!is_file($fullPath)) {
            abort(404);
        }
        return response()->download($fullPath, $attachment->original_name);
    }
}
