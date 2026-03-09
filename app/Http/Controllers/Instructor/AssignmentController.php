<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
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
            'allow_late_submission' => 'boolean',
            'is_required' => 'boolean',
        ]);

        $valid['course_id'] = $course->id;
        $valid['order'] = $course->assignments()->max('order') + 1;
        $valid['is_required'] = $request->boolean('is_required');
        $valid['allow_late_submission'] = $request->boolean('allow_late_submission', false);

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
            'allow_late_submission' => 'boolean',
            'is_required' => 'boolean',
        ]);
        $valid['is_required'] = $request->boolean('is_required');
        $valid['allow_late_submission'] = $request->boolean('allow_late_submission', false);

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

    public function submissions(Course $course, Assignment $assignment)
    {
        $this->authorize('update', $course);
        if ($assignment->course_id !== $course->id) abort(404);
        $submissions = $assignment->submissions()->with('user')->orderBy('submitted_at', 'desc')->get();
        return view('instructor.assignments.submissions', compact('course', 'assignment', 'submissions'));
    }

    public function gradeSubmission(Request $request, Course $course, Assignment $assignment, AssignmentSubmission $submission)
    {
        $this->authorize('update', $course);
        if ($assignment->course_id !== $course->id || $submission->assignment_id !== $assignment->id) abort(404);
        $valid = $request->validate([
            'score' => 'required|integer|min:0|max:' . (int) $assignment->max_score,
            'feedback' => 'nullable|string|max:2000',
        ]);
        $submission->update([
            'score' => $valid['score'],
            'feedback' => $valid['feedback'] ?? null,
            'graded_by' => $request->user()->id,
            'graded_at' => now(),
        ]);
        return redirect()->route('instructor.assignments.submissions', [$course, $assignment])
            ->with('success', 'Submission graded.');
    }
}
