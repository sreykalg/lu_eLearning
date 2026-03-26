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
            'quiz_weight' => 'required|integer|in:10,20',
            'assignment_weight' => 'required|integer|in:10,20',
            'midterm_weight' => 'required|integer|in:30,40',
            'final_weight' => 'required|integer|in:30,40',
        ]);

        $totalWeight = (int) $valid['quiz_weight']
            + (int) $valid['assignment_weight']
            + (int) $valid['midterm_weight']
            + (int) $valid['final_weight'];
        if ($totalWeight !== 100) {
            return back()
                ->withErrors(['grading' => 'Invalid grading setup. The total must be exactly 100.'])
                ->withInput();
        }

        $valid['instructor_id'] = $request->user()->id;
        $valid['slug'] = Str::slug($valid['title']);
        $valid['is_published'] = false;
        $valid['approval_status'] = Course::APPROVAL_PENDING;
        $valid['submitted_at'] = now();

        if ($request->hasFile('thumbnail')) {
            $valid['thumbnail'] = $request->file('thumbnail')->store('thumbnails', 'public');
        }

        Course::create($valid);
        return redirect()->route('instructor.courses.index')->with('success', 'Course created and submitted for approval.');
    }

    public function edit(Course $course)
    {
        if ($course->instructor_id !== auth()->id()) {
            abort(403);
        }
        if ($course->approval_status === Course::APPROVAL_PENDING) {
            return redirect()->route('instructor.courses.index')
                ->with('error', 'This course is under review. You can edit it after the HoD approves or requests changes.');
        }
        $course->load(['modules', 'lessons.videoQuizzes', 'quizzes.questions', 'assignments']);
        return view('instructor.courses.edit', compact('course'));
    }

    public function update(Request $request, Course $course)
    {
        $this->authorize('update', $course);
        $valid = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'level' => 'required|in:beginner,intermediate,advanced',
            'thumbnail' => 'nullable|image|max:2048',
            'quiz_weight' => 'required|integer|in:10,20',
            'assignment_weight' => 'required|integer|in:10,20',
            'midterm_weight' => 'required|integer|in:30,40',
            'final_weight' => 'required|integer|in:30,40',
        ]);

        $totalWeight = (int) $valid['quiz_weight']
            + (int) $valid['assignment_weight']
            + (int) $valid['midterm_weight']
            + (int) $valid['final_weight'];
        if ($totalWeight !== 100) {
            return back()
                ->withErrors(['grading' => 'Invalid grading setup. The total must be exactly 100.'])
                ->withInput();
        }

        if ($request->hasFile('thumbnail')) {
            $valid['thumbnail'] = $request->file('thumbnail')->store('thumbnails', 'public');
        }
        $course->update($valid);
        return redirect()->route('instructor.courses.edit', $course)->with('success', 'Course updated.');
    }

    public function destroy(Course $course)
    {
        $this->authorize('delete', $course);
        $course->delete();
        return redirect()->route('instructor.courses.index')->with('success', 'Course deleted.');
    }

    public function submitForApproval(Course $course)
    {
        $this->authorize('update', $course);
        if (!in_array($course->approval_status, [Course::APPROVAL_DRAFT, Course::APPROVAL_NEEDS_REVISION])) {
            return back()->with('error', 'Course cannot be submitted in current status.');
        }
        $course->update([
            'approval_status' => Course::APPROVAL_PENDING,
            'submitted_at' => now(),
            'revision_notes' => null,
        ]);
        return redirect()->route('instructor.courses.index')->with('success', 'Course submitted for approval.');
    }
}
