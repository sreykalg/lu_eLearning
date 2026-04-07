@extends('layouts.instructor-inner')

@include('instructor.course-builder.sidebar-styles')

@push('styles')
<style>
    .qz-edit-shell {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 1rem;
        box-shadow: 0 4px 24px rgba(15, 23, 42, 0.06);
        overflow: hidden;
    }
    .qz-edit-head {
        display: flex;
        flex-wrap: wrap;
        align-items: flex-start;
        justify-content: space-between;
        gap: 0.9rem;
        padding: 1.15rem 1.25rem 1rem;
        border-bottom: 1px solid #f1f5f9;
        background: linear-gradient(180deg, #fff 0%, #fafbfc 100%);
    }
    .qz-edit-head h4 {
        margin: 0;
        font-size: 1.2rem;
        font-weight: 800;
        letter-spacing: -0.02em;
        color: #0f172a;
    }
    .qz-edit-subtitle {
        margin: 0.3rem 0 0;
        font-size: 0.86rem;
        color: #64748b;
    }
    .qz-edit-actions { display: flex; flex-wrap: wrap; gap: 0.5rem; }
    .qz-edit-actions .btn { border-radius: 0.6rem; font-weight: 700; font-size: 0.78rem; text-transform: uppercase; letter-spacing: 0.03em; padding: 0.4rem 0.7rem; }
    .qz-edit-body { padding: 1.25rem; }
    .qz-edit-back {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        margin-bottom: 0.7rem;
        font-size: 0.84rem;
        font-weight: 700;
        color: #475569;
        text-decoration: none;
    }
    .qz-edit-back:hover { color: #0f172a; }
    .qz-block {
        border: 1px solid #e2e8f0;
        border-radius: 0.85rem;
        background: #fff;
        padding: 1rem 1rem 0.4rem;
        margin-bottom: 1rem;
    }
    .qz-section-title {
        font-size: 0.8rem;
        font-weight: 800;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        color: #64748b;
        margin-bottom: 0.85rem;
    }
    .question-block { border: 1px solid #e2e8f0; border-radius: 0.85rem; overflow: hidden; box-shadow: 0 1px 2px rgba(15,23,42,.05); }
    .question-block .card-body { padding: 0.9rem; }
</style>
@endpush

@section('content')
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="cb-wrap">
    <div class="cb-sidebar">
        @php $course->load(['modules', 'lessons', 'quizzes']); @endphp
        @include('instructor.course-builder.sidebar', ['course' => $course, 'quiz' => $quiz])
    </div>
    <div class="cb-main">
        <a href="{{ route('instructor.courses.edit', $course) }}" class="qz-edit-back">
            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Back to Course details
        </a>
        <div class="qz-edit-shell">
        <div class="qz-edit-head">
            <div>
                <h4>Edit Quiz</h4>
                <p class="qz-edit-subtitle">Refine quiz settings, attempts, and questions for this course module.</p>
            </div>
            <div class="qz-edit-actions">
                <button type="submit" form="quizForm" class="btn btn-outline-secondary btn-sm">Draft</button>
                <form action="{{ route('instructor.quizzes.destroy', [$course, $quiz]) }}" method="post" class="d-inline" onsubmit="return confirm('Delete this quiz?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-secondary btn-sm text-danger">Delete</button>
                </form>
                <button type="submit" form="quizForm" class="btn btn-sm" style="background:#0f172a;color:#fff;border:none;">Publish</button>
            </div>
        </div>
        <div class="qz-edit-body">
        <form action="{{ route('instructor.quizzes.update', [$course, $quiz]) }}" method="post" id="quizForm">
                    @csrf
                    @method('PUT')
                    <div class="qz-block">
                        <div class="qz-section-title">Quiz Details</div>
                    <div class="mb-3">
                        <label class="form-label">Title</label>
                        <input type="text" name="title" class="form-control" value="{{ old('title', $quiz->title) }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description (optional)</label>
                        <textarea name="description" class="form-control">{{ old('description', $quiz->description) }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Type</label>
                        <select name="type" class="form-select" required>
                            @foreach(['practice','midterm','final'] as $t)
                                <option value="{{ $t }}" {{ old('type', $quiz->type) === $t ? 'selected' : '' }}>{{ ucfirst($t) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Grading type</label>
                        <select name="grading_type" class="form-select">
                            <option value="auto" {{ old('grading_type', $quiz->grading_type ?? 'auto') === 'auto' ? 'selected' : '' }}>Auto-grading</option>
                            <option value="manual" {{ old('grading_type', $quiz->grading_type ?? 'auto') === 'manual' ? 'selected' : '' }}>Manual grading by instructor</option>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Full score (total points)</label>
                            <input type="number" name="total_points" id="quizTotalPoints" class="form-control" min="0" value="{{ old('total_points', $quiz->total_points) }}" placeholder="Auto from questions">
                            <small class="text-muted">Leave blank to use sum of question points</small>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Duration (minutes)</label>
                            <input type="number" name="duration_minutes" class="form-control" min="1" value="{{ old('duration_minutes', $quiz->duration_minutes) }}">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Passing score (%)</label>
                            <input type="number" name="passing_score" class="form-control" min="0" max="100" value="{{ old('passing_score', $quiz->passing_score) }}" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Max attempts</label>
                            <input type="number" name="max_attempts" class="form-control" min="1" value="{{ old('max_attempts', $quiz->max_attempts) }}">
                        </div>
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" name="is_required" value="1" class="form-check-input" id="is_required" {{ old('is_required', $quiz->is_required) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_required">Required</label>
                    </div>
                    </div>

                    <div class="qz-block">
                    <h5 class="mb-3">Questions <span class="text-muted small fw-normal">(Total: <span id="questionsTotalPoints">0</span> pts)</span></h5>
                    <div id="questionsContainer">
                        @foreach($quiz->questions as $i => $q)
                            @php
                                $opts = $q->options ?? [];
                                $correctIdx = collect($opts)->search(fn($x) => $x['is_correct'] ?? false);
                                $isMc = ($q->type ?? 'multiple_choice') === 'multiple_choice';
                                $expected = $isMc ? '' : ($opts[0]['text'] ?? '');
                            @endphp
                            <div class="card mb-3 question-block">
                                <div class="card-body">
                                    <input type="hidden" name="questions[{{ $i }}][id]" value="{{ $q->id }}">
                                    <div class="d-flex flex-wrap gap-2 align-items-start mb-2">
                                        <div class="flex-grow-1">
                                            <label class="form-label mb-0">Question {{ $i + 1 }}</label>
                                            <input type="text" name="questions[{{ $i }}][question]" class="form-control q-text" value="{{ old("questions.{$i}.question", $q->question) }}">
                                        </div>
                                        <div style="width: 100px;">
                                            <label class="form-label">Points</label>
                                            <input type="number" name="questions[{{ $i }}][points]" class="form-control q-points" min="0" value="{{ old("questions.{$i}.points", $q->points ?? 1) }}">
                                        </div>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label small">Question type</label>
                                        <select name="questions[{{ $i }}][type]" class="form-select form-select-sm q-type">
                                            <option value="multiple_choice" {{ ($q->type ?? 'multiple_choice') === 'multiple_choice' ? 'selected' : '' }}>Multiple choice</option>
                                            <option value="short_answer" {{ ($q->type ?? '') === 'short_answer' ? 'selected' : '' }}>Q&A / Short answer</option>
                                            <option value="code" {{ ($q->type ?? '') === 'code' ? 'selected' : '' }}>Code writing</option>
                                        </select>
                                    </div>
                                    <div class="q-options-wrap {{ $isMc ? '' : 'd-none' }}">
                                        <label class="form-label small">Options (select correct)</label>
                                        @foreach(range(0,3) as $j)
                                            @php $opt = $opts[$j] ?? []; @endphp
                                            <div class="input-group input-group-sm mb-1">
                                                <span class="input-group-text">
                                                    <input type="radio" name="questions[{{ $i }}][correct]" value="{{ $j }}" {{ ($correctIdx !== false && $correctIdx == $j) ? 'checked' : '' }}>
                                                </span>
                                                <input type="text" name="questions[{{ $i }}][options][{{ $j }}][text]" class="form-control" value="{{ $opt['text'] ?? '' }}" placeholder="Option {{ $j+1 }}">
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="q-expected-wrap {{ $isMc ? 'd-none' : '' }}">
                                        <label class="form-label small">Expected answer (for auto-grading)</label>
                                        <input type="text" name="questions[{{ $i }}][expected_answer]" class="form-control" value="{{ old("questions.{$i}.expected_answer", $expected) }}" placeholder="Expected answer (optional)">
                                    </div>
                                    <div class="d-flex justify-content-end mt-2 pt-2 border-top">
                                        <button type="button" class="btn btn-outline-danger btn-sm delete-question" title="Delete question">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/><path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/></svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <button type="button" class="btn btn-outline-secondary btn-sm mb-3" id="addQuestion">+ Add question</button>
                    </div>

                    <hr>
                    <button type="submit" class="btn btn-primary">Save Quiz</button>
                    <a href="{{ route('instructor.courses.edit', $course) }}" class="btn btn-outline-secondary">Cancel</a>
                </form>
        </div>
        </div>
</div>
</div>

    @push('scripts')
    <script>
        let qIndex = {{ $quiz->questions->count() }};
        function updateTotalPoints() {
            var total = 0;
            document.querySelectorAll('.q-points').forEach(function(inp) {
                total += parseInt(inp.value || 0, 10);
            });
            var el = document.getElementById('questionsTotalPoints');
            if (el) el.textContent = total;
        }
        function toggleQuestionType(block) {
            var type = block.querySelector('.q-type')?.value;
            var opts = block.querySelector('.q-options-wrap');
            var exp = block.querySelector('.q-expected-wrap');
            if (type === 'multiple_choice') {
                if (opts) opts.classList.remove('d-none');
                if (exp) exp.classList.add('d-none');
            } else {
                if (opts) opts.classList.add('d-none');
                if (exp) exp.classList.remove('d-none');
            }
        }
        document.getElementById('questionsContainer')?.addEventListener('change', function(e) {
            if (e.target.classList.contains('q-type')) toggleQuestionType(e.target.closest('.question-block'));
            if (e.target.classList.contains('q-points')) updateTotalPoints();
        });
        document.getElementById('questionsContainer')?.addEventListener('input', function(e) {
            if (e.target.classList.contains('q-points')) updateTotalPoints();
        });
        document.getElementById('addQuestion')?.addEventListener('click', function() {
            const html = `
                <div class="card mb-3 question-block">
                    <div class="card-body">
                        <div class="d-flex flex-wrap gap-2 align-items-start mb-2">
                            <div class="flex-grow-1">
                                <label class="form-label mb-0">Question ${qIndex + 1}</label>
                                <input type="text" name="questions[${qIndex}][question]" class="form-control q-text" placeholder="Question text">
                            </div>
                            <div style="width: 100px;">
                                <label class="form-label">Points</label>
                                <input type="number" name="questions[${qIndex}][points]" class="form-control q-points" min="0" value="1">
                            </div>
                        </div>
                        <div class="mb-2">
                            <label class="form-label small">Question type</label>
                            <select name="questions[${qIndex}][type]" class="form-select form-select-sm q-type">
                                <option value="multiple_choice">Multiple choice</option>
                                <option value="short_answer">Q&amp;A / Short answer</option>
                                <option value="code">Code writing</option>
                            </select>
                        </div>
                        <div class="q-options-wrap">
                            <label class="form-label small">Options (select correct)</label>
                            ${[0,1,2,3].map(j => `
                                <div class="input-group input-group-sm mb-1">
                                    <span class="input-group-text"><input type="radio" name="questions[${qIndex}][correct]" value="${j}"></span>
                                    <input type="text" name="questions[${qIndex}][options][${j}][text]" class="form-control" placeholder="Option ${j+1}">
                                </div>
                            `).join('')}
                        </div>
                        <div class="q-expected-wrap d-none">
                            <label class="form-label small">Expected answer (for auto-grading)</label>
                            <input type="text" name="questions[${qIndex}][expected_answer]" class="form-control" placeholder="Expected answer (optional)">
                        </div>
                        <div class="d-flex justify-content-end mt-2 pt-2 border-top">
                            <button type="button" class="btn btn-outline-danger btn-sm delete-question" title="Delete question">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/><path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/></svg>
                            </button>
                        </div>
                    </div>
                </div>`;
            document.getElementById('questionsContainer').insertAdjacentHTML('beforeend', html);
            qIndex++;
            updateTotalPoints();
        });
        document.querySelectorAll('.question-block').forEach(toggleQuestionType);
        updateTotalPoints();
        document.getElementById('questionsContainer')?.addEventListener('click', function(e) {
            if (e.target.closest('.delete-question')) {
                e.target.closest('.question-block')?.remove();
                updateTotalPoints();
            }
        });
    </script>
    @endpush
@endsection
