<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CourseController extends Controller
{
    public function index(Request $request): View
    {
        $courses = $request->user()->courses()->withCount(['lessons', 'quizzes', 'assignments'])->orderBy('order')->get();
        return view('instructor.courses.index', compact('courses'));
    }

    public function create(): View
    {
        return view('instructor.courses.create');
    }

    public function store(Request $request)
    {
        $valid = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'level' => 'required|in:beginner,intermediate,advanced',
            'thumbnail' => 'nullable|image|max:2048',
        ]);

        $valid['instructor_id'] = $request->user()->id;
        $valid['slug'] = Str::slug($valid['title']);
        $valid['is_published'] = false;

        if ($request->hasFile('thumbnail')) {
            $valid['thumbnail'] = $request->file('thumbnail')->store('thumbnails', 'public');
        }

        Course::create($valid);
        return redirect()->route('instructor.courses.index')->with('success', 'Course created.');
    }

    public function edit(Course $course): View
    {
        $this->authorize('update', $course);
        $course->load(['lessons.videoQuizzes', 'quizzes.questions', 'assignments']);
        return view('instructor.courses.edit', compact('course'));
    }

    public function update(Request $request, Course $course)
    {
        $this->authorize('update', $course);
        $valid = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'level' => 'required|in:beginner,intermediate,advanced',
            'is_published' => 'boolean',
            'thumbnail' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('thumbnail')) {
            $valid['thumbnail'] = $request->file('thumbnail')->store('thumbnails', 'public');
        }
        $valid['is_published'] = $request->boolean('is_published');

        $course->update($valid);
        return redirect()->route('instructor.courses.edit', $course)->with('success', 'Course updated.');
    }

    public function destroy(Course $course)
    {
        $this->authorize('delete', $course);
        $course->delete();
        return redirect()->route('instructor.courses.index')->with('success', 'Course deleted.');
    }
}
