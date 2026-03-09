<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GradeController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $enrollments = $user->enrollments()->with('course')->get();
        $submissions = $user->assignmentSubmissions()->with('assignment')->get();
        $attempts = $user->quizAttempts()->with('quiz')->get();
        return view('student.grades', compact('enrollments', 'submissions', 'attempts'));
    }
}
