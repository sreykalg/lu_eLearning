<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\User;
use App\Models\Enrollment;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'users' => User::count(),
            'courses' => Course::count(),
            'enrollments' => Enrollment::count(),
        ];
        $recentCourses = Course::with('instructor')->latest()->take(5)->get();
        $usersByRole = User::selectRaw('role, count(*) as total')->groupBy('role')->pluck('total', 'role');
        return view('admin.dashboard', compact('stats', 'recentCourses', 'usersByRole'));
    }
}
