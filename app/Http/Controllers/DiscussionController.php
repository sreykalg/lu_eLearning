<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Discussion;
use App\Models\DiscussionReply;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DiscussionController extends Controller
{
    public function index(Request $request): View
    {
        $discussions = Discussion::with(['user', 'course', 'replies.user'])
            ->when($request->course_id, fn ($q) => $q->where('course_id', $request->course_id))
            ->latest()
            ->paginate(15);

        $courses = Course::where('is_published', true)->orderBy('title')->get();

        return view('discussions.index', compact('discussions', 'courses'));
    }

    public function store(Request $request)
    {
        $valid = $request->validate([
            'title' => 'nullable|string|max:255',
            'body' => 'required|string',
            'course_id' => 'nullable|exists:courses,id',
            'lesson_id' => 'nullable|exists:lessons,id',
        ]);

        $valid['user_id'] = $request->user()->id;
        $valid['title'] = $valid['title'] ?: \Str::limit($valid['body'], 80);

        Discussion::create($valid);

        return redirect()->route('discussions.index')->with('success', 'Discussion posted!');
    }

    public function show(Discussion $discussion): View
    {
        $discussion->load(['user', 'course', 'lesson', 'replies.user', 'replies.replies.user']);
        return view('discussions.show', compact('discussion'));
    }

    public function reply(Request $request)
    {
        $valid = $request->validate([
            'discussion_id' => 'required|exists:discussions,id',
            'body' => 'required|string',
            'parent_id' => 'nullable|exists:discussion_replies,id',
        ]);

        $valid['user_id'] = $request->user()->id;
        $valid['is_instructor_answer'] = $request->user()->isInstructor();
        DiscussionReply::create($valid);

        return back()->with('success', 'Reply posted!');
    }
}
