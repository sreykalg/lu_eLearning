<?php

namespace App\Http\Controllers\Hod;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StudentEnrollmentController extends Controller
{
    public function index(): View
    {
        $courses = Course::where('is_published', true)
            ->with('instructor')
            ->withCount(['activeEnrollments as enrollments_count'])
            ->orderBy('title')
            ->get();

        return view('hod.students.index', compact('courses'));
    }

    public function show(Course $course): View
    {
        if (! $course->is_published) {
            abort(404);
        }

        $enrollments = $course->activeEnrollments()
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        $students = $enrollments->map(function ($enrollment) use ($course) {
            $user = $enrollment->user;

            // Practice quiz average (%)
            $practiceQuizAttempts = QuizAttempt::where('user_id', $user->id)
                ->whereHas('quiz', fn ($q) => $q->where('course_id', $course->id)->where('type', 'practice'))
                ->where('total_points', '>', 0)
                ->get();
            $quizAvg = $practiceQuizAttempts->isEmpty()
                ? null
                : (int) round($practiceQuizAttempts->avg(fn ($a) => ($a->score / $a->total_points) * 100));

            // Assignment average (%)
            $assignmentIds = $course->assignments()->pluck('id');
            $submissions = AssignmentSubmission::where('user_id', $user->id)
                ->whereIn('assignment_id', $assignmentIds)
                ->whereNotNull('score')
                ->get();
            $assignmentAvg = null;
            if ($submissions->isNotEmpty()) {
                $totalPct = 0;
                $count = 0;
                foreach ($submissions as $sub) {
                    $assignment = Assignment::find($sub->assignment_id);
                    if ($assignment && $assignment->max_score > 0) {
                        $totalPct += ($sub->score / $assignment->max_score) * 100;
                        $count++;
                    }
                }
                $assignmentAvg = $count > 0 ? (int) round($totalPct / $count) : null;
            }

            // Midterm score
            $midtermQuiz = Quiz::where('course_id', $course->id)->where('type', 'midterm')->first();
            $midtermScore = null;
            if ($midtermQuiz) {
                $attempt = QuizAttempt::where('user_id', $user->id)
                    ->where('quiz_id', $midtermQuiz->id)
                    ->where('total_points', '>', 0)
                    ->orderByDesc('submitted_at')
                    ->first();
                $midtermScore = $attempt ? (int) round(($attempt->score / $attempt->total_points) * 100) : null;
            }

            // Final exam score
            $finalQuiz = Quiz::where('course_id', $course->id)->where('type', 'final')->first();
            $finalScore = null;
            if ($finalQuiz) {
                $attempt = QuizAttempt::where('user_id', $user->id)
                    ->where('quiz_id', $finalQuiz->id)
                    ->where('total_points', '>', 0)
                    ->orderByDesc('submitted_at')
                    ->first();
                $finalScore = $attempt ? (int) round(($attempt->score / $attempt->total_points) * 100) : null;
            }

            // Attendance score (% lessons completed)
            $attendanceScore = null;
            $totalLessons = (int) $course->lessons()->count();
            if ($totalLessons > 0) {
                $completedLessons = (int) $user->lessonProgress()
                    ->whereHas('lesson', fn ($q) => $q->where('course_id', $course->id))
                    ->where('completed', true)
                    ->count();
                $attendanceScore = (int) round(($completedLessons / $totalLessons) * 100);
            }

            // Overall: weighted average of available scores
            $weights = [
                'quiz' => (int) ($course->quiz_weight ?? 10),
                'assignment' => (int) ($course->assignment_weight ?? 10),
                'midterm' => (int) ($course->midterm_weight ?? 30),
                'final' => (int) ($course->final_weight ?? 40),
                'attendance' => (int) ($course->attendance_weight ?? 10),
            ];
            $scores = [
                'quiz' => $quizAvg,
                'assignment' => $assignmentAvg,
                'midterm' => $midtermScore,
                'final' => $finalScore,
                'attendance' => $attendanceScore,
            ];
            $weightedTotal = 0.0;
            $appliedWeight = 0;
            foreach ($scores as $key => $score) {
                if ($score === null) {
                    continue;
                }
                $weightedTotal += $score * $weights[$key];
                $appliedWeight += $weights[$key];
            }
            $overall = $appliedWeight > 0 ? (int) round($weightedTotal / $appliedWeight) : null;

            return (object) [
                'enrollment' => $enrollment,
                'user' => $user,
                'quiz_avg' => $quizAvg,
                'assignment_avg' => $assignmentAvg,
                'midterm' => $midtermScore,
                'final' => $finalScore,
                'attendance' => $attendanceScore,
                'overall' => $overall,
            ];
        });

        return view('hod.students.show', compact('course', 'students'));
    }

    public function remove(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'enrollment_id' => 'required|exists:enrollments,id',
        ]);

        $enrollment = Enrollment::query()->active()->findOrFail($validated['enrollment_id']);
        $course = $enrollment->course;

        $enrollment->update(['archived_at' => now()]);

        return redirect()
            ->route('hod.students.show', $course)
            ->with('success', 'Student has been removed from the course.');
    }
}
