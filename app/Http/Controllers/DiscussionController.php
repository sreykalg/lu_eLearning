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
        $discussions = Discussion::with(['user', 'course', 'replies.user', 'likes', 'attachments'])
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
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|mimes:jpeg,jpg,png,gif,webp|max:5120', // 5MB each, images only
        ]);

        $valid['user_id'] = $request->user()->id;
        $valid['title'] = ($valid['title'] ?? null) ?: \Str::limit($valid['body'], 80);

        $discussion = Discussion::create(collect($valid)->except('attachments')->all());

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('discussion-attachments', 'public');
                $discussion->attachments()->create([
                    'path' => '/storage/' . $path,
                    'original_name' => $file->getClientOriginalName(),
                ]);
            }
        }

        return redirect()->route('discussions.index')->with('success', 'Discussion posted!');
    }

    public function like(Request $request, Discussion $discussion)
    {
        $like = $discussion->likes()->where('user_id', $request->user()->id)->first();
        if ($like) {
            $like->delete();
            $liked = false;
        } else {
            $discussion->likes()->create(['user_id' => $request->user()->id]);
            $liked = true;
        }
        return redirect()->back();
    }

    public function show(Discussion $discussion): View
    {
        $discussion->load(['user', 'course', 'lesson', 'replies.user', 'replies.attachments', 'replies.replies.user', 'replies.replies.attachments', 'attachments']);
        return view('discussions.show', compact('discussion'));
    }

    public function reply(Request $request)
    {
        $valid = $request->validate([
            'discussion_id' => 'required|exists:discussions,id',
            'body' => 'required|string',
            'parent_id' => 'nullable|exists:discussion_replies,id',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|mimes:jpeg,jpg,png,gif,webp|max:5120',
        ]);

        $valid['user_id'] = $request->user()->id;
        $valid['is_instructor_answer'] = $request->user()->isInstructor();
        $reply = DiscussionReply::create(collect($valid)->except('attachments')->all());

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('discussion-reply-attachments', 'public');
                $reply->attachments()->create([
                    'path' => '/storage/' . $path,
                    'original_name' => $file->getClientOriginalName(),
                ]);
            }
        }

        return back()->with('success', 'Reply posted!');
    }
}
