<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Course;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AnnouncementController extends Controller
{
    public function index(Request $request): View
    {
        $announcements = Announcement::whereHas('course', fn ($q) => $q->where('instructor_id', $request->user()->id))
            ->with(['course'])
            ->latest()
            ->paginate(20);

        return view('instructor.announcements.index', compact('announcements'));
    }

    public function create(Request $request): View
    {
        $courses = $request->user()->courses()->orderBy('title')->get();
        $recentAnnouncements = Announcement::where('instructor_id', $request->user()->id)
            ->with('course')
            ->latest()
            ->limit(8)
            ->get();

        return view('instructor.announcements.create', compact('courses', 'recentAnnouncements'));
    }

    public function store(Request $request): RedirectResponse
    {
        $valid = $request->validate([
            'course_id' => ['required', 'exists:courses,id'],
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string', 'max:5000'],
            'expires_at' => ['nullable', 'date', 'after:now'],
        ]);

        $course = Course::findOrFail($valid['course_id']);
        if ($course->instructor_id !== $request->user()->id) {
            abort(403);
        }

        Announcement::create([
            'course_id' => $valid['course_id'],
            'instructor_id' => $request->user()->id,
            'title' => $valid['title'],
            'body' => $valid['body'],
            'expires_at' => $valid['expires_at'] ?? null,
        ]);

        return redirect()->route('instructor.announcements.index')->with('success', 'Announcement posted successfully.');
    }
}
