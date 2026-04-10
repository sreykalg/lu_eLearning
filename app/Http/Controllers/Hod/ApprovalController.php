<?php

namespace App\Http\Controllers\Hod;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;

class ApprovalController extends Controller
{
    public function index(Request $request): View
    {
        $query = $this->scopedCoursesQuery($request)
            ->with('instructor')
            ->withCount(['lessons', 'modules'])
            ->whereIn('approval_status', [Course::APPROVAL_PENDING, Course::APPROVAL_APPROVED, Course::APPROVAL_NEEDS_REVISION]);
        if ($request->filled('status')) {
            $query->where('approval_status', $request->status);
        }
        $courses = $query->orderBy('updated_at', 'desc')->get();

        $baseCountsQuery = $this->scopedCoursesQuery($request)
            ->whereIn('approval_status', [Course::APPROVAL_PENDING, Course::APPROVAL_APPROVED, Course::APPROVAL_NEEDS_REVISION]);

        $counts = [
            'all' => (clone $baseCountsQuery)->count(),
            'pending' => (clone $baseCountsQuery)->where('approval_status', Course::APPROVAL_PENDING)->count(),
            'approved' => (clone $baseCountsQuery)->where('approval_status', Course::APPROVAL_APPROVED)->count(),
            'needs_revision' => (clone $baseCountsQuery)->where('approval_status', Course::APPROVAL_NEEDS_REVISION)->count(),
        ];

        return view('hod.approval', compact('courses', 'counts'));
    }

    public function approve(Request $request, Course $course): RedirectResponse
    {
        abort_unless($this->canManageCourseApproval($request, $course), 403);

        $course->update([
            'approval_status' => Course::APPROVAL_APPROVED,
            'is_published' => true,
            'approved_at' => now(),
            'revision_notes' => null,
        ]);
        return back();
    }

    public function requestChanges(Request $request, Course $course): RedirectResponse
    {
        abort_unless($this->canManageCourseApproval($request, $course), 403);

        $notes = $request->validate(['revision_notes' => 'required|string|max:2000'])['revision_notes'];
        $course->update([
            'approval_status' => Course::APPROVAL_NEEDS_REVISION,
            'revision_notes' => $notes,
        ]);
        return back()->with('success', 'Revision requested. Instructor will be notified.');
    }

    private function scopedCoursesQuery(Request $request): Builder
    {
        $hod = $request->user();
        $query = Course::query();

        if ($hod->isAdmin()) {
            return $query;
        }

        if (empty($hod->department)) {
            return $query->whereRaw('1 = 0');
        }

        return $query->whereHas('instructor', function (Builder $instructorQuery) use ($hod) {
            $instructorQuery->where('department', $hod->department);
        });
    }

    private function canManageCourseApproval(Request $request, Course $course): bool
    {
        $hod = $request->user();

        if ($hod->isAdmin()) {
            return true;
        }

        if (empty($hod->department)) {
            return false;
        }

        $course->loadMissing('instructor');

        return $course->instructor && $course->instructor->department === $hod->department;
    }
}
