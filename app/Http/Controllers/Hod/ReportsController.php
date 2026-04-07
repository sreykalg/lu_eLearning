<?php

namespace App\Http\Controllers\Hod;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\Course;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use Illuminate\View\View;

class ReportsController extends Controller
{
    public function index(): View
    {
        $attempts = QuizAttempt::where('total_points', '>', 0)->get();
        $avgQuiz = $attempts->isEmpty() ? 0 : (int) round($attempts->avg(fn ($a) => ($a->score / $a->total_points) * 100));
        $submissions = AssignmentSubmission::count();
        $assignments = Assignment::count();
        $submissionRate = $assignments > 0 ? (int) round(($submissions / max($assignments, 1)) * 100) : 0;
        $examAttempts = QuizAttempt::whereHas('quiz', fn ($q) => $q->whereIn('type', ['midterm', 'final']))->count();
        $examQuizzes = Quiz::whereIn('type', ['midterm', 'final'])->count();
        $courses = Course::where('is_published', true)->withCount(['lessons', 'activeEnrollments as enrollments_count'])->get();
        return view('hod.reports', compact('avgQuiz', 'submissionRate', 'examAttempts', 'examQuizzes', 'courses'));
    }
}
