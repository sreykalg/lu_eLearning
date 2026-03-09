<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SubmissionsController extends Controller
{
    public function index(Request $request): View
    {
        $courses = $request->user()->courses()
            ->with(['assignments' => fn ($q) => $q->withCount('submissions'), 'quizzes' => fn ($q) => $q->withCount('attempts')])
            ->orderBy('title')
            ->get();
        return view('instructor.submissions.index', compact('courses'));
    }
}
