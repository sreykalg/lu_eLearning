<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GradeController extends Controller
{
    protected function percentToLetter(float $pct): string
    {
        if ($pct >= 97) return 'A+';
        if ($pct >= 93) return 'A';
        if ($pct >= 90) return 'A-';
        if ($pct >= 87) return 'B+';
        if ($pct >= 83) return 'B';
        if ($pct >= 80) return 'B-';
        if ($pct >= 77) return 'C+';
        if ($pct >= 73) return 'C';
        if ($pct >= 70) return 'C-';
        if ($pct >= 67) return 'D+';
        if ($pct >= 63) return 'D';
        if ($pct >= 60) return 'D-';
        return 'F';
    }

    public function index(Request $request): View
    {
        $user = $request->user();
        $enrollments = $user->enrollments()->with(['course.assignments', 'course.quizzes'])->get();
        $submissions = $user->assignmentSubmissions()->with('assignment')->get()->keyBy(fn ($s) => $s->assignment_id);
        $attempts = $user->quizAttempts()->with('quiz')->get();

        $coursesData = collect();
        $allPercents = collect();

        foreach ($enrollments as $e) {
            $course = $e->course;
            $items = collect();
            $quizPercents = collect();
            $assignmentPercents = collect();
            $midtermPct = null;
            $finalPct = null;

            foreach ($course->assignments ?? [] as $a) {
                $sub = $submissions->get($a->id);
                if ($sub && $sub->score !== null && ($a->max_score ?? 0) > 0) {
                    $earned = (float) $sub->score;
                    $max = (float) $a->max_score;
                    $pct = (int) round(($earned / $max) * 100);
                    $assignmentPercents->push($pct);
                    $items->push([
                        'title' => $a->title,
                        'type' => 'Assignment',
                        'earned' => $earned,
                        'max' => $max,
                        'pct' => $pct,
                    ]);
                    $allPercents->push($pct);
                }
            }

            foreach ($course->quizzes ?? [] as $q) {
                $best = $attempts->where('quiz_id', $q->id)->filter(fn ($a) => $a->total_points > 0)->sortByDesc(fn ($a) => $a->score / $a->total_points)->first();
                if ($best) {
                    $earned = (float) $best->score;
                    $max = (float) $best->total_points;
                    $pct = (int) round(($earned / $max) * 100);
                    $quizType = strtolower($q->type ?? 'practice');
                    if ($quizType === 'midterm') {
                        $midtermPct = $pct;
                    } elseif ($quizType === 'final') {
                        $finalPct = $pct;
                    } else {
                        $quizPercents->push($pct);
                    }
                    $typeLabel = match (strtolower($q->type ?? 'practice')) {
                        'midterm' => 'Midterm',
                        'final' => 'Final',
                        default => 'Quiz',
                    };
                    $items->push([
                        'title' => $q->title,
                        'type' => $typeLabel,
                        'earned' => $earned,
                        'max' => $max,
                        'pct' => $pct,
                    ]);
                    $allPercents->push($pct);
                }
            }

            $quizAvg = $quizPercents->isEmpty() ? null : (int) round($quizPercents->avg());
            $assignmentAvg = $assignmentPercents->isEmpty() ? null : (int) round($assignmentPercents->avg());
            $weights = [
                'quiz' => (int) ($course->quiz_weight ?? 20),
                'assignment' => (int) ($course->assignment_weight ?? 20),
                'midterm' => (int) ($course->midterm_weight ?? 30),
                'final' => (int) ($course->final_weight ?? 30),
            ];
            $componentScores = [
                'quiz' => $quizAvg,
                'assignment' => $assignmentAvg,
                'midterm' => $midtermPct,
                'final' => $finalPct,
            ];
            $weightedTotal = 0.0;
            $appliedWeight = 0;
            foreach ($componentScores as $key => $score) {
                if ($score === null) {
                    continue;
                }
                $weightedTotal += $score * $weights[$key];
                $appliedWeight += $weights[$key];
            }
            $coursePct = $appliedWeight > 0 ? (int) round($weightedTotal / $appliedWeight) : null;
            $letter = $coursePct !== null ? $this->percentToLetter($coursePct) : null;

            $coursesData->push([
                'course' => $course,
                'items' => $items->sortByDesc('pct')->values(),
                'current_pct' => $coursePct,
                'letter' => $letter,
            ]);
        }

        $overallAvg = $allPercents->isEmpty() ? 0 : (int) round($allPercents->avg());
        $highestPct = $allPercents->isEmpty() ? 0 : $allPercents->max();
        $highestLetter = $allPercents->isEmpty() ? null : $this->percentToLetter($highestPct);

        return view('student.grades', [
            'coursesData' => $coursesData,
            'overallAvg' => $overallAvg,
            'highestPct' => $highestPct,
            'highestLetter' => $highestLetter,
            'coursesCount' => $enrollments->count(),
        ]);
    }
}
