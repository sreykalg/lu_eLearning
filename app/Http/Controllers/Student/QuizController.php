<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class QuizController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $quizzes = \App\Models\Quiz::whereHas('course', fn ($q) => $q->whereHas('enrollments', fn ($e) => $e->where('user_id', $user->id)))
            ->with('course')
            ->orderBy('created_at')
            ->get();
        $attempts = $user->quizAttempts()->get()->keyBy('quiz_id');
        return view('student.quizzes', compact('quizzes', 'attempts'));
    }
}
