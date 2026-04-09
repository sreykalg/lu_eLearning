@extends('layouts.instructor-inner')

@include('instructor.course-builder.sidebar-styles')

@push('styles')
<style>
    .qz-edit-shell {
        background: transparent;
        border: 0;
        border-radius: 0;
        box-shadow: none;
        overflow: visible;
    }
    .qz-edit-head {
        display: flex;
        flex-wrap: wrap;
        align-items: flex-start;
        justify-content: space-between;
        gap: 0.9rem;
        padding: 1rem 1.1rem;
        border: 1px solid #e2e8f0;
        border-radius: 0.9rem;
        background: #fff;
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
    .qz-edit-body { margin-top: 0.9rem; padding: 0; }
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
        border: 0;
        border-radius: 0;
        background: transparent;
        padding: 0;
        margin-bottom: 0;
    }
    .qz-section-title {
        font-size: 0.8rem;
        font-weight: 800;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        color: #64748b;
        margin-bottom: 1rem;
    }
    .qz-form-flat {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 0.9rem;
        padding: 1rem 1.1rem;
    }
    .qz-field {
        padding-bottom: 1rem;
        margin-bottom: 1rem;
        border-bottom: 1px solid #f1f5f9;
    }
    .qz-field:last-of-type { border-bottom: 0; margin-bottom: 0.8rem; padding-bottom: 0; }
    .qz-actions-row {
        display: flex;
        justify-content: flex-end;
        gap: 0.55rem;
        margin-top: 0.95rem;
    }
    .question-block {
        border: 1px solid #dbe4f0;
        border-radius: 0.9rem;
        background: linear-gradient(180deg, #ffffff 0%, #fbfdff 100%);
        padding: 0.9rem;
        box-shadow: 0 2px 10px rgba(15, 23, 42, 0.04);
    }
    .question-block + .question-block { margin-top: 0.75rem; }
    .question-block .question-body { padding: 0; }
    .qz-question-head {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 0.65rem;
        margin-bottom: 0.75rem;
        flex-wrap: wrap;
    }
    .qz-question-index {
        display: inline-flex;
        align-items: center;
        padding: 0.2rem 0.55rem;
        border-radius: 9999px;
        background: #eef2ff;
        border: 1px solid #c7d2fe;
        color: #3730a3;
        font-size: 0.74rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        margin-top: -0.45rem;
    }
    .qz-points-wrap {
        width: 104px;
        flex-shrink: 0;
    }
    .qz-points-wrap .form-label {
        margin-bottom: 0.28rem;
        font-size: 0.76rem;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        font-weight: 700;
    }
    .qz-points-input {
        text-align: center;
        font-weight: 700;
        min-height: 40px;
    }
    .qz-type-wrap {
        width: 230px;
        flex-shrink: 0;
    }
    .qz-type-wrap .form-label {
        margin-bottom: 0.28rem;
        font-size: 0.76rem;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        font-weight: 700;
    }
    .qz-type-wrap .form-select {
        min-height: 40px;
        height: 40px;
        line-height: 1.2;
    }
    .qz-question-label {
        margin-bottom: 0.3rem;
        font-size: 0.76rem;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        font-weight: 700;
    }
    .qz-question-text {
        min-height: 42px;
    }
    .qz-divider {
        border-top: 1px solid #e8edf5;
        margin-top: 0.7rem;
        padding-top: 0.6rem;
    }
    .qz-delete-btn {
        border-radius: 0.55rem;
        border-color: #f3c4cc;
        color: #be123c;
        background: #fff;
    }
    .qz-delete-btn:hover {
        border-color: #fb7185;
        color: #9f1239;
        background: #fff1f2;
    }
    .qz-add-btn {
        border-color: #0f172a;
        color: #0f172a;
        border-radius: 0.65rem;
        font-weight: 700;
    }
    .qz-add-btn:hover { background: rgba(15, 23, 42, 0.06); color: #0f172a; border-color: #0f172a; }
    .qz-score-alert {
        margin-top: 0.75rem;
        margin-bottom: 0.85rem;
        padding: 0.6rem 0.75rem;
        border-radius: 0.65rem;
        border: 1px solid #fecaca;
        background: #fef2f2;
        color: #b91c1c;
        font-size: 0.84rem;
        font-weight: 600;
        display: none;
    }
    .qz-score-alert.ok {
        border-color: #bbf7d0;
        background: #f0fdf4;
        color: #166534;
        display: block;
    }
    .qz-score-alert.warn { display: block; }
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
                <p class="qz-edit-subtitle">Set quiz type, points, and questions with a cleaner workflow.</p>
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
                    <div class="qz-form-flat">
                        <div class="qz-section-title">Quiz Details</div>
                    <div class="qz-field">
                        <div class="row g-3">
                            <div class="col-lg-6">
                                <label class="form-label">Title</label>
                                <input type="text" name="title" class="form-control" value="{{ old('title', $quiz->title) }}" required>
                            </div>
                            <div class="col-lg-3">
                                <label class="form-label">Type</label>
                                <select name="type" class="form-select" required>
                                    @foreach(['practice','midterm','final'] as $t)
                                        <option value="{{ $t }}" {{ old('type', $quiz->type) === $t ? 'selected' : '' }}>{{ ucfirst($t) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-3">
                                <label class="form-label">Full score</label>
                                <input type="number" name="total_points" id="quizTotalPoints" class="form-control" min="0" value="{{ old('total_points', $quiz->total_points) }}" placeholder="e.g. 100">
                            </div>
                        </div>
                    </div>
                    <div class="qz-field">
                        <label class="form-label">Description (optional)</label>
                        <textarea name="description" class="form-control">{{ old('description', $quiz->description) }}</textarea>
                    </div>
                    <div class="mb-0">
                        <div class="small text-muted">Grading is fixed to manual by instructor.</div>
                    </div>
                    <input type="hidden" name="grading_type" value="manual">
                    <input type="hidden" name="passing_score" value="{{ old('passing_score', $quiz->passing_score ?? 0) }}">
                    <input type="hidden" name="duration_minutes" value="{{ old('duration_minutes', $quiz->duration_minutes) }}">
                    <input type="hidden" name="max_attempts" value="{{ old('max_attempts', $quiz->max_attempts) }}">
                    <input type="hidden" name="is_required" value="{{ old('is_required', $quiz->is_required) ? 1 : 0 }}">
                    </div>

                    <div class="qz-form-flat mt-3">
                    <h5 class="mb-3">Questions <span class="text-muted small fw-normal">(Total: <span id="questionsTotalPoints">0</span> pts)</span></h5>
                    <div id="questionsContainer">
                        @foreach($quiz->questions as $i => $q)
                            @php
                                $opts = $q->options ?? [];
                                $correctIdx = collect($opts)->search(fn($x) => $x['is_correct'] ?? false);
                                $isMc = ($q->type ?? 'multiple_choice') === 'multiple_choice';
                            @endphp
                            <div class="question-block">
                                <div class="question-body">
                                    <input type="hidden" name="questions[{{ $i }}][id]" value="{{ $q->id }}">
                                    <div class="qz-question-head">
                                        <span class="qz-question-index">Question {{ $i + 1 }}</span>
                                        <div class="d-flex align-items-end gap-2 flex-wrap">
                                            <div class="qz-type-wrap">
                                                <label class="form-label">Question type</label>
                                                <select name="questions[{{ $i }}][type]" class="form-select form-select-sm q-type">
                                                    <option value="multiple_choice" {{ ($q->type ?? 'multiple_choice') === 'multiple_choice' ? 'selected' : '' }}>Multiple choice</option>
                                                    <option value="short_answer" {{ ($q->type ?? '') === 'short_answer' ? 'selected' : '' }}>Q&A / Short answer</option>
                                                    <option value="code" {{ ($q->type ?? '') === 'code' ? 'selected' : '' }}>Code writing</option>
                                                </select>
                                            </div>
                                            <div class="qz-points-wrap">
                                                <label class="form-label">Points</label>
                                                <input type="number" name="questions[{{ $i }}][points]" class="form-control q-points qz-points-input" min="0" value="{{ old("questions.{$i}.points", $q->points ?? 1) }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="form-label qz-question-label">Question text</label>
                                        <input type="text" name="questions[{{ $i }}][question]" class="form-control q-text qz-question-text" value="{{ old("questions.{$i}.question", $q->question) }}">
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
                                    <div class="d-flex justify-content-end qz-divider">
                                        <button type="button" class="btn btn-sm delete-question qz-delete-btn" title="Delete question">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/><path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/></svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div id="scoreMismatchAlert" class="qz-score-alert"></div>
                    <button type="button" class="btn btn-sm qz-add-btn mb-3" id="addQuestion">+ Add question</button>
                    </div>

                    <div class="qz-actions-row">
                        <a href="{{ route('instructor.courses.edit', $course) }}" class="btn btn-outline-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Save Quiz</button>
                    </div>
                </form>
        </div>
        </div>
</div>
</div>

    @push('scripts')
    <script>
        let qIndex = {{ $quiz->questions->count() }};
        function validateScoreMatch() {
            var totalQuestions = 0;
            document.querySelectorAll('.q-points').forEach(function(inp) {
                totalQuestions += parseInt(inp.value || 0, 10);
            });
            var fullScoreInput = document.getElementById('quizTotalPoints');
            var fullScore = parseInt(fullScoreInput?.value || 0, 10);
            var alertEl = document.getElementById('scoreMismatchAlert');
            if (!alertEl) return true;

            if (!fullScoreInput || fullScoreInput.value === '') {
                alertEl.className = 'qz-score-alert';
                alertEl.textContent = '';
                return true;
            }

            if (fullScore !== totalQuestions) {
                alertEl.className = 'qz-score-alert warn';
                alertEl.textContent = 'Question points total (' + totalQuestions + ') must equal Full score (' + fullScore + ').';
                return false;
            }

            alertEl.className = 'qz-score-alert ok';
            alertEl.textContent = 'Perfect: Question points match Full score (' + fullScore + ').';
            return true;
        }
        function updateTotalPoints() {
            var total = 0;
            document.querySelectorAll('.q-points').forEach(function(inp) {
                total += parseInt(inp.value || 0, 10);
            });
            var el = document.getElementById('questionsTotalPoints');
            if (el) el.textContent = total;
            validateScoreMatch();
        }
        function toggleQuestionType(block) {
            var type = block.querySelector('.q-type')?.value;
            var opts = block.querySelector('.q-options-wrap');
            if (type === 'multiple_choice') {
                if (opts) opts.classList.remove('d-none');
            } else {
                if (opts) opts.classList.add('d-none');
            }
        }
        document.getElementById('questionsContainer')?.addEventListener('change', function(e) {
            if (e.target.classList.contains('q-type')) toggleQuestionType(e.target.closest('.question-block'));
            if (e.target.classList.contains('q-points')) updateTotalPoints();
        });
        document.getElementById('questionsContainer')?.addEventListener('input', function(e) {
            if (e.target.classList.contains('q-points')) updateTotalPoints();
        });
        document.getElementById('quizTotalPoints')?.addEventListener('input', validateScoreMatch);
        document.getElementById('addQuestion')?.addEventListener('click', function() {
            const html = `
                <div class="question-block">
                    <div class="question-body">
                        <div class="qz-question-head">
                            <span class="qz-question-index">Question ${qIndex + 1}</span>
                            <div class="d-flex align-items-end gap-2 flex-wrap">
                                <div class="qz-type-wrap">
                                    <label class="form-label">Question type</label>
                                    <select name="questions[${qIndex}][type]" class="form-select form-select-sm q-type">
                                        <option value="multiple_choice">Multiple choice</option>
                                        <option value="short_answer">Q&amp;A / Short answer</option>
                                        <option value="code">Code writing</option>
                                    </select>
                                </div>
                                <div class="qz-points-wrap">
                                    <label class="form-label">Points</label>
                                    <input type="number" name="questions[${qIndex}][points]" class="form-control q-points qz-points-input" min="0" value="1">
                                </div>
                            </div>
                        </div>
                        <div>
                            <label class="form-label qz-question-label">Question text</label>
                            <input type="text" name="questions[${qIndex}][question]" class="form-control q-text qz-question-text" placeholder="Question text">
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
                        <div class="d-flex justify-content-end qz-divider">
                            <button type="button" class="btn btn-sm delete-question qz-delete-btn" title="Delete question">
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
        document.getElementById('quizForm')?.addEventListener('submit', function(e) {
            if (!validateScoreMatch()) {
                e.preventDefault();
                document.getElementById('scoreMismatchAlert')?.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        });
    </script>
    @endpush
@endsection
