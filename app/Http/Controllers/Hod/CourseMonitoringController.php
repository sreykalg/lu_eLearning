<?php

namespace App\Http\Controllers\Hod;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\Course;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\User;
use Illuminate\View\View;

class CourseMonitoringController extends Controller
{
    public function index(): View
    {
        $courses = Course::query()
            ->where('is_published', true)
            ->where('approval_status', Course::APPROVAL_APPROVED)
            ->with('instructor')
            ->withCount('enrollments')
            ->orderBy('title')
            ->get();

        return view('hod.monitoring.index', compact('courses'));
    }

    public function show(Course $course): View
    {
        if (! $course->is_published || $course->approval_status !== Course::APPROVAL_APPROVED) {
            abort(404);
        }

        $students = $course->enrollments()
            ->with('user')
            ->orderByDesc('created_at')
            ->get()
            ->filter(fn ($enrollment) => $enrollment->user && $enrollment->user->isStudent())
            ->values()
            ->map(function ($enrollment) use ($course) {
                $performance = $this->calculatePerformance($course, $enrollment->user);
                return (object) [
                    'enrollment' => $enrollment,
                    'user' => $enrollment->user,
                    'overall' => $performance['overall'],
                ];
            });

        return view('hod.monitoring.show', compact('course', 'students'));
    }

    public function student(Course $course, User $student): View
    {
        if (! $course->is_published || $course->approval_status !== Course::APPROVAL_APPROVED || ! $student->isStudent()) {
            abort(404);
        }

        $enrolled = $course->enrollments()->where('user_id', $student->id)->exists();
        abort_unless($enrolled, 404);

        $performance = $this->calculatePerformance($course, $student);

        return view('hod.monitoring.student', compact('course', 'student', 'performance'));
    }

    private function calculatePerformance(Course $course, User $user): array
    {
        $practiceQuizAttempts = QuizAttempt::where('user_id', $user->id)
            ->whereHas('quiz', fn ($q) => $q->where('course_id', $course->id)->where('type', 'practice'))
            ->where('total_points', '>', 0)
            ->get();
        $quizAvg = $practiceQuizAttempts->isEmpty()
            ? null
            : (int) round($practiceQuizAttempts->avg(fn ($a) => ($a->score / $a->total_points) * 100));

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

        $attendanceScore = null;
        $totalLessons = (int) $course->lessons()->count();
        if ($totalLessons > 0) {
            $completedLessons = (int) $user->lessonProgress()
                ->whereHas('lesson', fn ($q) => $q->where('course_id', $course->id))
                ->where('completed', true)
                ->count();
            $attendanceScore = (int) round(($completedLessons / $totalLessons) * 100);
        }

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

        return [
            'quiz_avg' => $quizAvg,
            'assignment_avg' => $assignmentAvg,
            'midterm' => $midtermScore,
            'final' => $finalScore,
            'attendance' => $attendanceScore,
            'overall' => $appliedWeight > 0 ? (int) round($weightedTotal / $appliedWeight) : null,
        ];
    }
}
