<?php

namespace App\Http\Controllers\Hod;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ApprovalController extends Controller
{
    public function index(Request $request): View
    {
        $query = Course::with('instructor')->withCount(['lessons', 'modules'])
            ->whereIn('approval_status', [Course::APPROVAL_PENDING, Course::APPROVAL_APPROVED, Course::APPROVAL_NEEDS_REVISION]);
        if ($request->filled('status')) {
            $query->where('approval_status', $request->status);
        }
        $courses = $query->orderBy('updated_at', 'desc')->get();

        $counts = [
            'all' => Course::whereIn('approval_status', [Course::APPROVAL_PENDING, Course::APPROVAL_APPROVED, Course::APPROVAL_NEEDS_REVISION])->count(),
            'pending' => Course::where('approval_status', Course::APPROVAL_PENDING)->count(),
            'approved' => Course::where('approval_status', Course::APPROVAL_APPROVED)->count(),
            'needs_revision' => Course::where('approval_status', Course::APPROVAL_NEEDS_REVISION)->count(),
        ];

        return view('hod.approval', compact('courses', 'counts'));
    }

    public function approve(Course $course): RedirectResponse
    {
        $course->update([
            'approval_status' => Course::APPROVAL_APPROVED,
            'is_published' => true,
            'approved_at' => now(),
            'revision_notes' => null,
        ]);
        return back()->with('success', 'Course approved.');
    }

    public function requestChanges(Request $request, Course $course): RedirectResponse
    {
        $notes = $request->validate(['revision_notes' => 'required|string|max:2000'])['revision_notes'];
        $course->update([
            'approval_status' => Course::APPROVAL_NEEDS_REVISION,
            'revision_notes' => $notes,
        ]);
        return back()->with('success', 'Revision requested. Instructor will be notified.');
    }
}
