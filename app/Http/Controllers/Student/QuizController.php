<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class QuizController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $quizzes = Quiz::whereHas('course', fn ($q) => $q->whereHas('enrollments', fn ($e) => $e->where('user_id', $user->id)))
            ->with('course')
            ->orderBy('created_at')
            ->get();
        $attempts = $user->quizAttempts()->get()->keyBy('quiz_id');
        return view('student.quizzes', compact('quizzes', 'attempts'));
    }

    public function show(Request $request, Course $course, Quiz $quiz): View|RedirectResponse
    {
        if ($quiz->course_id !== $course->id) {
            abort(404);
        }
        $enrollment = Enrollment::where('user_id', $request->user()->id)
            ->where('course_id', $course->id)
            ->first();
        if (!$enrollment && !$request->user()->isInstructor() && !$request->user()->isHeadOfDept()) {
            return redirect()->route('courses.show', $course)
                ->with('error', 'Please enroll in the course to access this quiz.');
        }
        $quiz->load(['questions' => fn ($q) => $q->orderBy('order')]);
        $attempts = $quiz->userAttempts($request->user())->orderByDesc('submitted_at')->get();
        return view('student.quiz-show', compact('course', 'quiz', 'attempts'));
    }

    public function submit(Request $request, Course $course, Quiz $quiz): RedirectResponse
    {
        if ($quiz->course_id !== $course->id) {
            abort(404);
        }
        $enrollment = Enrollment::where('user_id', $request->user()->id)
            ->where('course_id', $course->id)
            ->first();
        if (!$enrollment && !$request->user()->isInstructor() && !$request->user()->isHeadOfDept()) {
            return redirect()->route('courses.show', $course)->with('error', 'Please enroll to take this quiz.');
        }
        $quiz->load('questions');
        $answers = $request->input('answers', []);
        $score = 0;
        $totalPoints = 0;
        foreach ($quiz->questions as $q) {
            $pts = (int) ($q->points ?? 1);
            $totalPoints += $pts;
            $qType = $q->type ?? 'multiple_choice';
            if ($qType === 'multiple_choice') {
                $selected = isset($answers[$q->id]) ? (int) $answers[$q->id] : null;
                if ($selected !== null && $q->getCorrectOptionIndex() === $selected) {
                    $score += $pts;
                }
            } else {
                $submitted = trim((string) ($answers[$q->id] ?? ''));
                $expected = $q->options[0]['text'] ?? null;
                if ($expected !== null && $expected !== '') {
                    $expectedTrim = trim($expected);
                    $match = $qType === 'short_answer'
                        ? strcasecmp($submitted, $expectedTrim) === 0
                        : $submitted === $expectedTrim;
                    if ($match) {
                        $score += $pts;
                    }
                }
            }
        }
        $total = ($quiz->total_points > 0 ? $quiz->total_points : null) ?? $totalPoints ?: 1;
        $percentage = $total > 0 ? (int) round(($score / $total) * 100) : 0;
        $passingScore = (int) ($quiz->passing_score ?? 0);
        $passed = $passingScore > 0 ? $percentage >= $passingScore : null;

        QuizAttempt::create([
            'user_id' => $request->user()->id,
            'quiz_id' => $quiz->id,
            'answers' => $answers,
            'score' => $score,
            'total_points' => $total,
            'passed' => $passed,
            'started_at' => now(),
            'submitted_at' => now(),
        ]);
        return redirect()->route('student.quizzes.show', [$course, $quiz])
            ->with('success', 'Quiz submitted! Score: ' . $score . '/' . $total);
    }
}
