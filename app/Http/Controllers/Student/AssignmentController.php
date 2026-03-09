<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\AssignmentSubmission;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AssignmentController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $assignments = \App\Models\Assignment::whereHas('course', fn ($q) => $q->whereHas('enrollments', fn ($e) => $e->where('user_id', $user->id)))
            ->with('course')
            ->orderBy('due_at')
            ->get();
        $submissions = AssignmentSubmission::where('user_id', $user->id)->get()->keyBy('assignment_id');
        return view('student.assignments', compact('assignments', 'submissions'));
    }
}
