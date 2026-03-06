<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LessonController extends Controller
{
    public function create(Course $course)
    {
        $this->authorize('update', $course);
        return view('instructor.lessons.create', compact('course'));
    }

    public function store(Request $request, Course $course)
    {
        $this->authorize('update', $course);
        $valid = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'video' => 'nullable|file|mimes:mp4,mov,webm|max:512000', // 512MB
            'video_url' => 'nullable|string',
            'video_duration' => 'nullable|integer|min:0',
            'is_free' => 'boolean',
        ]);

        $valid['course_id'] = $course->id;
        $valid['slug'] = Str::slug($valid['title']);
        $valid['is_free'] = $request->boolean('is_free');
        $valid['order'] = $course->lessons()->max('order') + 1;

        if ($request->hasFile('video')) {
            $valid['video_url'] = '/storage/' . $request->file('video')->store('videos', 'public');
        } elseif (!empty(trim($valid['video_url'] ?? ''))) {
            $valid['video_url'] = trim($valid['video_url']);
        } else {
            unset($valid['video_url']);
        }
        unset($valid['video']);

        $valid['video_duration'] = $valid['video_duration'] ?? null;

        Lesson::create($valid);
        return redirect()->route('instructor.courses.edit', $course)->with('success', 'Lesson added.');
    }

    public function edit(Course $course, Lesson $lesson)
    {
        $this->authorize('update', $course);
        if ($lesson->course_id !== $course->id) abort(404);
        $lesson->load('videoQuizzes');
        return view('instructor.lessons.edit', compact('course', 'lesson'));
    }

    public function update(Request $request, Course $course, Lesson $lesson)
    {
        $this->authorize('update', $course);
        if ($lesson->course_id !== $course->id) abort(404);

        $valid = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'video' => 'nullable|file|mimes:mp4,mov,webm|max:512000',
            'video_url' => 'nullable|string',
            'video_duration' => 'nullable|integer|min:0',
            'is_free' => 'boolean',
        ]);

        $valid['is_free'] = $request->boolean('is_free');

        if ($request->hasFile('video')) {
            $valid['video_url'] = '/storage/' . $request->file('video')->store('videos', 'public');
        } elseif (!empty(trim($valid['video_url'] ?? ''))) {
            $valid['video_url'] = trim($valid['video_url']);
        } else {
            unset($valid['video_url']);
        }
        unset($valid['video']);

        $lesson->update($valid);
        return redirect()->route('instructor.lessons.edit', [$course, $lesson])->with('success', 'Lesson updated.');
    }

    public function destroy(Course $course, Lesson $lesson)
    {
        $this->authorize('update', $course);
        if ($lesson->course_id !== $course->id) abort(404);
        $lesson->delete();
        return redirect()->route('instructor.courses.edit', $course)->with('success', 'Lesson deleted.');
    }
}
