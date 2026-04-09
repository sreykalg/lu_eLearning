<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class QuizController extends Controller
{
    public function create(Course $course)
    {
        $this->authorize('update', $course);
        $course->load('modules');
        return view('instructor.quizzes.create', compact('course'));
    }

    public function store(Request $request, Course $course)
    {
        $this->authorize('update', $course);
        $valid = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:practice,midterm,final',
            'grading_type' => 'required|in:auto,manual',
            'duration_minutes' => 'nullable|integer|min:1',
            'passing_score' => 'required|integer|min:0|max:100',
            'total_points' => 'nullable|integer|min:0',
            'max_attempts' => 'nullable|integer|min:1',
            'is_required' => 'boolean',
            'module_id' => 'nullable|exists:modules,id',
        ]);

        $valid['course_id'] = $course->id;
        $valid['total_points'] = $valid['total_points'] ?? null;
        if (empty($valid['module_id']) || !\App\Models\Module::where('id', $valid['module_id'])->where('course_id', $course->id)->exists()) {
            $valid['module_id'] = null;
        }
        $valid['order'] = $course->quizzes()->max('order') + 1;
        $valid['is_required'] = $request->boolean('is_required');

        $quiz = Quiz::create($valid);
        $this->syncQuestions($request, $quiz);
        return redirect()->route('instructor.courses.edit', $course)->with('success', 'Quiz created.');
    }

    public function edit(Course $course, Quiz $quiz)
    {
        $this->authorize('update', $course);
        if ($quiz->course_id !== $course->id) abort(404);
        $quiz->load('questions');
        return view('instructor.quizzes.edit', compact('course', 'quiz'));
    }

    public function update(Request $request, Course $course, Quiz $quiz)
    {
        $this->authorize('update', $course);
        if ($quiz->course_id !== $course->id) abort(404);

        $valid = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:practice,midterm,final',
            'grading_type' => 'required|in:auto,manual',
            'duration_minutes' => 'nullable|integer|min:1',
            'passing_score' => 'required|integer|min:0|max:100',
            'total_points' => 'nullable|integer|min:0',
            'max_attempts' => 'nullable|integer|min:1',
            'is_required' => 'boolean',
        ]);
        $valid['is_required'] = $request->boolean('is_required');
        $valid['total_points'] = $valid['total_points'] ?? null;

        $quiz->update($valid);
        $this->syncQuestions($request, $quiz);
        return redirect()->route('instructor.quizzes.edit', [$course, $quiz]);
    }

    public function destroy(Course $course, Quiz $quiz)
    {
        $this->authorize('update', $course);
        if ($quiz->course_id !== $course->id) abort(404);
        $quiz->delete();
        return redirect()->route('instructor.courses.edit', $course)->with('success', 'Quiz deleted.');
    }

    public function attempts(Course $course, Quiz $quiz)
    {
        $this->authorize('update', $course);
        if ($quiz->course_id !== $course->id) abort(404);
        $attempts = $quiz->attempts()->with('user')->orderByDesc('submitted_at')->get();
        return view('instructor.quizzes.attempts', compact('course', 'quiz', 'attempts'));
    }

    private function syncQuestions(Request $request, Quiz $quiz): void
    {
        $questions = $request->input('questions', []);
        $ids = [];
        foreach ($questions as $i => $q) {
            if (empty(trim($q['question'] ?? ''))) continue;

            $type = in_array($q['type'] ?? '', ['multiple_choice', 'short_answer', 'code', 'file_upload']) ? $q['type'] : 'multiple_choice';
            $points = max(0, (int) ($q['points'] ?? 1));
            $existingQuestion = null;
            if (!empty($q['id'])) {
                $existingQuestion = QuizQuestion::where('quiz_id', $quiz->id)->find($q['id']);
            }

            if ($type === 'multiple_choice') {
                $rawOptions = $q['options'] ?? [];
                $correctIdx = isset($q['correct']) ? (int) $q['correct'] : null;
                $options = [];
                foreach ($rawOptions as $j => $o) {
                    $text = trim($o['text'] ?? '');
                    if ($text === '') continue;
                    $options[] = [
                        'text' => $text,
                        'is_correct' => $correctIdx !== null && (int) $j === $correctIdx,
                    ];
                }
                if (count($options) < 2) continue;
                if ($correctIdx === null || count(array_filter($options, fn ($o) => $o['is_correct'])) !== 1) continue;
            } elseif (in_array($type, ['short_answer', 'code'])) {
                $expected = trim($q['expected_answer'] ?? '');
                $options = $expected !== '' ? [['text' => $expected]] : [];
            } else {
                $promptFile = null;
                $existingPrompt = $existingQuestion->options['prompt_file'] ?? null;
                $removePrompt = (int) ($q['remove_prompt_file'] ?? 0) === 1;
                $uploadedPrompt = $request->file("questions.$i.prompt_file");

                if ($existingPrompt && $removePrompt && !empty($existingPrompt['path'])) {
                    Storage::disk('public')->delete($existingPrompt['path']);
                    $existingPrompt = null;
                }

                if ($uploadedPrompt) {
                    if ($existingPrompt && !empty($existingPrompt['path'])) {
                        Storage::disk('public')->delete($existingPrompt['path']);
                    }
                    $storedPromptPath = $uploadedPrompt->store('quiz-question-files', 'public');
                    $promptFile = [
                        'path' => $storedPromptPath,
                        'name' => $uploadedPrompt->getClientOriginalName(),
                    ];
                } else {
                    $promptFile = $existingPrompt;
                }

                $options = $promptFile ? ['prompt_file' => $promptFile] : [];
            }

            if ($type !== 'file_upload' && $existingQuestion) {
                $existingPrompt = $existingQuestion->options['prompt_file'] ?? null;
                if ($existingPrompt && !empty($existingPrompt['path'])) {
                    Storage::disk('public')->delete($existingPrompt['path']);
                }
            }

            $data = [
                'quiz_id' => $quiz->id,
                'question' => trim($q['question']),
                'type' => $type,
                'options' => $type === 'multiple_choice' ? $options : $options,
                'order' => $i,
                'points' => $points,
            ];
            if (!empty($q['id'])) {
                $question = $existingQuestion ?: QuizQuestion::where('quiz_id', $quiz->id)->find($q['id']);
                if ($question) {
                    $question->update($data);
                    $ids[] = $question->id;
                    continue;
                }
            }
            $question = QuizQuestion::create($data);
            $ids[] = $question->id;
        }
        $quiz->questions()->whereNotIn('id', $ids)->delete();
    }
}
