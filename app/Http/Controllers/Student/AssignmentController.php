<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AssignmentController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $assignments = Assignment::whereHas('course', fn ($q) => $q->whereHas('enrollments', fn ($e) => $e->where('user_id', $user->id)))
            ->with('course')
            ->orderBy('due_at')
            ->get();
        $submissions = AssignmentSubmission::where('user_id', $user->id)->get()->keyBy('assignment_id');
        return view('student.assignments', compact('assignments', 'submissions'));
    }

    public function show(Request $request, Course $course, Assignment $assignment): View|RedirectResponse
    {
        if ($assignment->course_id !== $course->id) {
            abort(404);
        }
        $enrollment = Enrollment::where('user_id', $request->user()->id)
            ->where('course_id', $course->id)
            ->first();
        if (!$enrollment && !$request->user()->isInstructor() && !$request->user()->isHeadOfDept()) {
            return redirect()->route('courses.show', $course)
                ->with('error', 'Please enroll in the course to view this assignment.');
        }
        $submission = AssignmentSubmission::where('user_id', $request->user()->id)
            ->where('assignment_id', $assignment->id)
            ->first();
        return view('student.assignment-show', compact('course', 'assignment', 'submission'));
    }

    public function submit(Request $request, Course $course, Assignment $assignment): RedirectResponse
    {
        if ($assignment->course_id !== $course->id) {
            abort(404);
        }
        $enrollment = Enrollment::where('user_id', $request->user()->id)
            ->where('course_id', $course->id)
            ->first();
        if (!$enrollment) {
            return redirect()->route('courses.show', $course)->with('error', 'Please enroll to submit.');
        }
        $exists = AssignmentSubmission::where('user_id', $request->user()->id)
            ->where('assignment_id', $assignment->id)
            ->exists();
        if ($exists) {
            return redirect()->route('student.assignments.show', [$course, $assignment])
                ->with('error', 'You have already submitted this assignment.');
        }
        $valid = $request->validate([
            'content' => 'nullable|string',
            'file' => 'nullable|file|max:10240',
        ]);
        $path = null;
        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('submissions', 'public');
        }
        AssignmentSubmission::create([
            'user_id' => $request->user()->id,
            'assignment_id' => $assignment->id,
            'content' => $valid['content'] ?? null,
            'file_path' => $path,
            'submitted_at' => now(),
        ]);
        return redirect()->route('student.assignments.show', [$course, $assignment])
            ->with('success', 'Assignment submitted successfully.');
    }
}
