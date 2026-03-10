<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Discussion;
use App\Models\DiscussionReply;
use App\Notifications\MentionInDiscussionNotification;
use App\Support\MentionParser;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DiscussionController extends Controller
{
    public function mentionUsers(Request $request)
    {
        $q = $request->query('q', '');
        $users = \App\Models\User::query()
            ->where('id', '!=', $request->user()->id)
            ->when($q !== '', fn ($query) => $query->where('name', 'like', '%' . $q . '%'))
            ->orderBy('name')
            ->limit(15)
            ->get(['id', 'name'])
            ->map(fn ($u) => ['id' => $u->id, 'name' => $u->name]);
        return response()->json($users);
    }

    public function index(Request $request): View
    {
        $discussions = Discussion::with(['user', 'course', 'replies.user', 'replies.likes', 'replies.replies.user', 'replies.replies.likes',  'likes', 'attachments'])
            ->when($request->course_id, fn ($q) => $q->where('course_id', $request->course_id))
            ->orderByRaw('is_pinned DESC, created_at DESC')
            ->paginate(15);

        $courses = Course::where('is_published', true)->orderBy('title')->get();

        $mentionUsers = [];
        if ($request->user()) {
            $mentionUsers = \App\Models\User::query()
                ->where('id', '!=', $request->user()->id)
                ->orderBy('name')
                ->limit(50)
                ->get(['id', 'name'])
                ->map(fn ($u) => ['id' => $u->id, 'name' => $u->name])
                ->values()
                ->all();
        }

        return view('discussions.index', compact('discussions', 'courses', 'mentionUsers'));
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

        $mentioned = MentionParser::parse($valid['body'], $request->user()->id);
        foreach ($mentioned as $user) {
            $user->notify(new MentionInDiscussionNotification(
                $request->user()->name,
                $discussion->load('user'),
                null,
                \Str::limit($valid['body'], 80)
            ));
        }

        return redirect()->route('discussions.index');
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

    public function replyLike(Request $request, DiscussionReply $reply)
    {
        $like = $reply->likes()->where('user_id', $request->user()->id)->first();
        if ($like) {
            $like->delete();
        } else {
            $reply->likes()->create(['user_id' => $request->user()->id]);
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

        $discussion = $reply->discussion;
        $mentioned = MentionParser::parse($valid['body'], $request->user()->id);
        foreach ($mentioned as $user) {
            $user->notify(new MentionInDiscussionNotification(
                $request->user()->name,
                $discussion->load('user'),
                $reply->id,
                \Str::limit($valid['body'], 80)
            ));
        }

        return back();
    }

    public function pin(Request $request, Discussion $discussion)
    {
        if (!$request->user()->isInstructor() && !$request->user()->isHeadOfDept()) {
            abort(403);
        }
        $discussion->update(['is_pinned' => !$discussion->is_pinned]);
        return redirect()->back();
    }

    public function destroy(Request $request, Discussion $discussion)
    {
        if (!$request->user()->isInstructor() && !$request->user()->isHeadOfDept() && $request->user()->id !== $discussion->user_id) {
            abort(403);
        }
        $discussion->delete();
        return redirect()->route('discussions.index');
    }
}
