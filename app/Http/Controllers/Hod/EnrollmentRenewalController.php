<?php

namespace App\Http\Controllers\Hod;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EnrollmentRenewalController extends Controller
{
    public function create(Request $request): View
    {
        $cutoffInput = $request->query('cutoff_date')
            ?? $request->query('cutoff')
            ?? now()->subYear()->format('Y-m-d');
        $courseIds = $request->query('course_ids', []);
        if (! is_array($courseIds)) {
            $courseIds = [];
        }
        $courseIds = array_filter(array_map('intval', $courseIds));

        $cutoff = Carbon::parse($cutoffInput)->endOfDay();

        $previewQuery = Enrollment::query()->active()->where('created_at', '<=', $cutoff);
        if ($courseIds !== []) {
            $previewQuery->whereIn('course_id', $courseIds);
        }
        $previewCount = (int) $previewQuery->count();

        $courses = Course::where('is_published', true)->orderBy('title')->get(['id', 'title']);

        return view('hod.enrollments.archive', compact('cutoffInput', 'previewCount', 'courses', 'courseIds'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'cutoff_date' => ['required', 'date'],
            'course_ids' => ['nullable', 'array'],
            'course_ids.*' => ['integer', 'exists:courses,id'],
        ]);

        $cutoff = Carbon::parse($validated['cutoff_date'])->endOfDay();

        $query = Enrollment::query()->active()->where('created_at', '<=', $cutoff);

        $courseIds = $validated['course_ids'] ?? [];
        if ($courseIds !== []) {
            $query->whereIn('course_id', $courseIds);
        }

        $count = $query->count();
        $query->update(['archived_at' => now()]);

        return redirect()
            ->route('hod.enrollments.archive')
            ->with('success', $count === 1
                ? '1 enrollment was archived.'
                : "{$count} enrollments were archived.");
    }
}
