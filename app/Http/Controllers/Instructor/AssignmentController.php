<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Course;
use Illuminate\Http\Request;

class AssignmentController extends Controller
{
    public function create(Course $course)
    {
        $this->authorize('update', $course);
        $course->load('lessons');
        return view('instructor.assignments.create', compact('course'));
    }

    public function store(Request $request, Course $course)
    {
        $this->authorize('update', $course);
        $valid = $request->validate([
            'title' => 'required|string|max:255',
            'instructions' => 'nullable|string',
            'lesson_id' => 'nullable|exists:lessons,id',
            'max_score' => 'required|integer|min:0',
            'grading_type' => 'required|in:auto,manual',
            'due_at' => 'nullable|date',
            'is_required' => 'boolean',
        ]);

        $valid['course_id'] = $course->id;
        $valid['order'] = $course->assignments()->max('order') + 1;
        $valid['is_required'] = $request->boolean('is_required');

        Assignment::create($valid);
        return redirect()->route('instructor.courses.edit', $course)->with('success', 'Assignment added.');
    }

    public function edit(Course $course, Assignment $assignment)
    {
        $this->authorize('update', $course);
        if ($assignment->course_id !== $course->id) abort(404);
        $course->load('lessons');
        return view('instructor.assignments.edit', compact('course', 'assignment'));
    }

    public function update(Request $request, Course $course, Assignment $assignment)
    {
        $this->authorize('update', $course);
        if ($assignment->course_id !== $course->id) abort(404);

        $valid = $request->validate([
            'title' => 'required|string|max:255',
            'instructions' => 'nullable|string',
            'lesson_id' => 'nullable|exists:lessons,id',
            'max_score' => 'required|integer|min:0',
            'grading_type' => 'required|in:auto,manual',
            'due_at' => 'nullable|date',
            'is_required' => 'boolean',
        ]);
        $valid['is_required'] = $request->boolean('is_required');

        $assignment->update($valid);
        return redirect()->route('instructor.courses.edit', $course)->with('success', 'Assignment updated.');
    }

    public function destroy(Course $course, Assignment $assignment)
    {
        $this->authorize('update', $course);
        if ($assignment->course_id !== $course->id) abort(404);
        $assignment->delete();
        return redirect()->route('instructor.courses.edit', $course)->with('success', 'Assignment deleted.');
    }
}
