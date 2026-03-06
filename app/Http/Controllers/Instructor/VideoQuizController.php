<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Models\VideoQuiz;
use Illuminate\Http\Request;

class VideoQuizController extends Controller
{
    public function store(Request $request)
    {
        $valid = $request->validate([
            'lesson_id' => 'required|exists:lessons,id',
            'timestamp_minutes' => 'required|integer|min:0',
            'timestamp_seconds' => 'nullable|integer|min:0|max:59',
            'question' => 'required|string',
            'options' => 'required|array|min:2',
            'options.*.text' => 'required|string',
            'correct_index' => 'required|integer|min:0|max:3',
        ]);

        $lesson = Lesson::findOrFail($valid['lesson_id']);
        $this->authorize('update', $lesson->course);

        $totalSeconds = ($valid['timestamp_minutes'] ?? 0) * 60 + ($valid['timestamp_seconds'] ?? 0);
        $correctIdx = (int) $valid['correct_index'];

        $options = collect($valid['options'])
            ->filter(fn ($o) => !empty(trim($o['text'] ?? '')))
            ->map(fn ($o, $origIdx) => ['text' => trim($o['text']), 'is_correct' => (int) $origIdx === $correctIdx])
            ->values()
            ->toArray();
        if (count($options) < 2) {
            return back()->withErrors(['options' => 'At least 2 options required.']);
        }

        VideoQuiz::create([
            'lesson_id' => $lesson->id,
            'timestamp_seconds' => $totalSeconds,
            'question' => $valid['question'],
            'options' => $options,
        ]);

        return back()->with('success', 'In-video quiz added at ' . gmdate('i:s', $totalSeconds));
    }

    public function update(Request $request, VideoQuiz $videoQuiz)
    {
        $this->authorize('update', $videoQuiz->lesson->course);

        $valid = $request->validate([
            'timestamp_minutes' => 'required|integer|min:0',
            'timestamp_seconds' => 'nullable|integer|min:0|max:59',
            'question' => 'required|string',
            'options' => 'required|array|min:2',
            'options.*.text' => 'required|string',
            'correct_index' => 'required|integer|min:0|max:3',
        ]);

        $totalSeconds = $valid['timestamp_minutes'] * 60 + ($valid['timestamp_seconds'] ?? 0);
        $correctIdx = (int) $valid['correct_index'];
        $options = collect($valid['options'])->map(fn ($o, $i) => ['text' => trim($o['text']), 'is_correct' => $i === $correctIdx])->toArray();

        $videoQuiz->update([
            'timestamp_seconds' => $totalSeconds,
            'question' => $valid['question'],
            'options' => $options,
        ]);

        return back()->with('success', 'In-video quiz updated.');
    }

    public function destroy(VideoQuiz $videoQuiz)
    {
        $this->authorize('update', $videoQuiz->lesson->course);
        $videoQuiz->delete();
        return back()->with('success', 'In-video quiz removed.');
    }
}
