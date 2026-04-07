<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\AssignmentSubmission;
use App\Models\Course;
use App\Models\LessonProgress;
use App\Models\QuizAttempt;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CourseController extends Controller
{
    public function myCourses(Request $request): View
    {
        $courses = $request->user()
            ->courses()
            ->withCount(['lessons', 'quizzes', 'assignments', 'activeEnrollments as enrollments_count'])
            ->orderBy('title')
            ->get();

        $selectedCourse = null;
        $students = collect();

        $selected = $request->query('course');
        if ($selected) {
            $selectedCourse = $courses->firstWhere('slug', $selected);
        }
        if (! $selectedCourse && $courses->isNotEmpty()) {
            $selectedCourse = $courses->first();
        }
        if ($selectedCourse) {
            $students = $selectedCourse->activeEnrollments()
                ->with('user')
                ->orderByDesc('created_at')
                ->get()
                ->filter(fn ($enrollment) => $enrollment->user && $enrollment->user->isStudent())
                ->values()
                ->map(function ($enrollment) use ($selectedCourse) {
                    $studentId = $enrollment->user_id;
                    $courseId = $selectedCourse->id;

                    $lastQuizAt = QuizAttempt::query()
                        ->where('user_id', $studentId)
                        ->whereHas('quiz', fn ($q) => $q->where('course_id', $courseId))
                        ->max('submitted_at');

                    $lastAssignmentAt = AssignmentSubmission::query()
                        ->where('user_id', $studentId)
                        ->whereHas('assignment', fn ($q) => $q->where('course_id', $courseId))
                        ->max('submitted_at');

                    $lastLessonAt = LessonProgress::query()
                        ->where('user_id', $studentId)
                        ->whereHas('lesson', fn ($q) => $q->where('course_id', $courseId))
                        ->max('updated_at');

                    $candidates = collect([$lastQuizAt, $lastAssignmentAt, $lastLessonAt])
                        ->filter()
                        ->map(fn ($date) => \Illuminate\Support\Carbon::parse($date));

                    $enrollment->last_activity_at = $candidates->isNotEmpty() ? $candidates->max() : null;

                    return $enrollment;
                });
        }

        return view('instructor/my-courses/index', compact('courses', 'selectedCourse', 'students'));
    }

    public function removeStudent(Request $request, Course $course, User $student): RedirectResponse
    {
        abort_unless($course->instructor_id === $request->user()->id, 403);

        $enrollment = $course->activeEnrollments()
            ->where('user_id', $student->id)
            ->first();

        if (! $enrollment) {
            return back()->with('error', 'Student is not enrolled in this course.');
        }

        $enrollment->update(['archived_at' => now()]);

        return redirect()
            ->route('instructor.my-courses', ['course' => $course->slug])
            ->with('success', 'Student removed from course.');
    }

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
            'attendance_weight' => 'required|integer|in:10',
        ]);

        $totalWeight = (int) $valid['quiz_weight']
            + (int) $valid['assignment_weight']
            + (int) $valid['midterm_weight']
            + (int) $valid['final_weight']
            + (int) $valid['attendance_weight'];
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
            'attendance_weight' => 'required|integer|in:10',
        ]);

        $totalWeight = (int) $valid['quiz_weight']
            + (int) $valid['assignment_weight']
            + (int) $valid['midterm_weight']
            + (int) $valid['final_weight']
            + (int) $valid['attendance_weight'];
        if ($totalWeight !== 100) {
            return back()
                ->withErrors(['grading' => 'Invalid grading setup. The total must be exactly 100.'])
                ->withInput();
        }

        if ($request->hasFile('thumbnail')) {
            $valid['thumbnail'] = $request->file('thumbnail')->store('thumbnails', 'public');
        }
        $course->update($valid);
        return redirect()->route('instructor.courses.edit', $course);
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

    public function togglePublish(Course $course): RedirectResponse
    {
        $this->authorize('update', $course);

        if ($course->approval_status !== Course::APPROVAL_APPROVED) {
            return back()->with('error', 'Only approved courses can be published or unpublished.');
        }

        $course->update([
            'is_published' => ! $course->is_published,
        ]);

        return back()->with(
            'success',
            $course->is_published
                ? 'Course is now active for students.'
                : 'Course is now inactive for students.'
        );
    }
}
