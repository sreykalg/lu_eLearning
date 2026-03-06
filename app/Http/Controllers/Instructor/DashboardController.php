<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $courses = $request->user()->courses()->withCount(['lessons', 'enrollments'])->orderBy('order')->get();
        return view('instructor.dashboard', compact('courses'));
    }
}
