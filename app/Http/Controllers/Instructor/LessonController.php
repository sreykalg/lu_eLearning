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
        $course->load('modules');
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
            'module_id' => 'nullable|exists:modules,id',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|mimes:pdf,doc,docx,ppt,pptx|max:20480', // 20MB each
        ]);

        $valid['course_id'] = $course->id;
        if (empty($valid['module_id']) || !\App\Models\Module::where('id', $valid['module_id'])->where('course_id', $course->id)->exists()) {
            $valid['module_id'] = null;
        }
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

        $lesson = Lesson::create(collect($valid)->except('attachments')->all());
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('lesson-attachments', 'public');
                $lesson->attachments()->create(['path' => '/storage/' . $path, 'original_name' => $file->getClientOriginalName()]);
            }
        }
        return redirect()->route('instructor.courses.edit', $course)->with('success', 'Lesson added.');
    }

    public function edit(Course $course, Lesson $lesson)
    {
        $this->authorize('update', $course);
        if ($lesson->course_id !== $course->id) abort(404);
        $lesson->load(['videoQuizzes', 'attachments']);
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
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|mimes:pdf,doc,docx,ppt,pptx|max:20480',
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

        $lesson->update(collect($valid)->except('attachments')->all());
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('lesson-attachments', 'public');
                $lesson->attachments()->create(['path' => '/storage/' . $path, 'original_name' => $file->getClientOriginalName()]);
            }
        }
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
