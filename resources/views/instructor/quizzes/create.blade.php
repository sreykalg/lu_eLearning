@extends('layouts.instructor-inner')

@include('instructor.course-builder.sidebar-styles')

@section('content')
<div class="cb-wrap">
    <div class="cb-sidebar">
        @php $course->load(['modules', 'lessons', 'quizzes']); @endphp
        @include('instructor.course-builder.sidebar', ['course' => $course])
    </div>
    <div class="cb-main">
        <h4 class="mb-4 fw-bold">New Quiz</h4>
        <form action="{{ route('instructor.quizzes.store', $course) }}" method="post" id="quizForm">
            @csrf
            <div class="mb-3">
                <label class="form-label">Module (optional)</label>
                <select name="module_id" class="form-select">
                    <option value="">— No module —</option>
                    @foreach($course->modules as $m)
                        <option value="{{ $m->id }}" {{ old('module_id', request('module_id')) == $m->id ? 'selected' : '' }}>{{ $m->title }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Title</label>
                <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required>
                @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Description (optional)</label>
                <textarea name="description" class="form-control">{{ old('description') }}</textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Type</label>
                <select name="type" class="form-select @error('type') is-invalid @enderror" required>
                    <option value="practice" {{ old('type') === 'practice' ? 'selected' : '' }}>Practice</option>
                    <option value="midterm" {{ old('type') === 'midterm' ? 'selected' : '' }}>Midterm</option>
                    <option value="final" {{ old('type') === 'final' ? 'selected' : '' }}>Final Exam</option>
                </select>
                @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Grading type</label>
                <select name="grading_type" class="form-select">
                    <option value="auto" {{ old('grading_type', 'auto') === 'auto' ? 'selected' : '' }}>Auto-grading</option>
                    <option value="manual" {{ old('grading_type', 'auto') === 'manual' ? 'selected' : '' }}>Manual grading by instructor</option>
                </select>
                <small class="text-muted">Auto-grading uses correct answers; manual grading requires instructor review.</small>
            </div>
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label class="form-label">Full score (total points)</label>
                    <input type="number" name="total_points" id="quizTotalPoints" class="form-control" min="0" value="{{ old('total_points') }}" placeholder="Auto from questions">
                    <small class="text-muted">Leave blank to use sum of question points</small>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Duration (minutes)</label>
                    <input type="number" name="duration_minutes" class="form-control" min="1" value="{{ old('duration_minutes') }}">
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Passing score (%)</label>
                    <input type="number" name="passing_score" class="form-control" min="0" max="100" value="{{ old('passing_score', 70) }}" required>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Max attempts</label>
                    <input type="number" name="max_attempts" class="form-control" min="1" value="{{ old('max_attempts') }}">
                </div>
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" name="is_required" value="1" class="form-check-input" id="is_required" {{ old('is_required') ? 'checked' : '' }}>
                <label class="form-check-label" for="is_required">Required</label>
            </div>

            <hr>
            <h5 class="mb-3">Questions <span class="text-muted small fw-normal">(Total: <span id="questionsTotalPoints">0</span> pts)</span></h5>
            <div id="questionsContainer">
                @for($i = 0; $i < 3; $i++)
                    <div class="card mb-3 question-block">
                        <div class="card-body">
                            <div class="d-flex flex-wrap gap-2 align-items-start mb-2">
                                <div class="flex-grow-1">
                                    <label class="form-label mb-0">Question {{ $i + 1 }}</label>
                                    <input type="text" name="questions[{{ $i }}][question]" class="form-control q-text" placeholder="Question text">
                                </div>
                                <div style="width: 100px;">
                                    <label class="form-label">Points</label>
                                    <input type="number" name="questions[{{ $i }}][points]" class="form-control q-points" min="0" value="1">
                                </div>
                            </div>
                            <div class="mb-2">
                                <label class="form-label small">Question type</label>
                                <select name="questions[{{ $i }}][type]" class="form-select form-select-sm q-type">
                                    <option value="multiple_choice">Multiple choice</option>
                                    <option value="short_answer">Q&amp;A / Short answer</option>
                                    <option value="code">Code writing</option>
                                </select>
                            </div>
                            <div class="q-options-wrap">
                                <label class="form-label small">Options (select correct)</label>
                                @foreach(range(0,3) as $j)
                                    <div class="input-group input-group-sm mb-1">
                                        <span class="input-group-text"><input type="radio" name="questions[{{ $i }}][correct]" value="{{ $j }}"></span>
                                        <input type="text" name="questions[{{ $i }}][options][{{ $j }}][text]" class="form-control" placeholder="Option {{ $j+1 }}">
                                    </div>
                                @endforeach
                            </div>
                            <div class="q-expected-wrap d-none">
                                <label class="form-label small">Expected answer (for auto-grading)</label>
                                <input type="text" name="questions[{{ $i }}][expected_answer]" class="form-control" placeholder="Expected answer (optional)">
                            </div>
                            <div class="d-flex justify-content-end mt-2 pt-2 border-top">
                                <button type="button" class="btn btn-outline-danger btn-sm delete-question" title="Delete question">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/><path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/></svg>
                                </button>
                            </div>
                        </div>
                    </div>
                @endfor
            </div>
            <button type="button" class="btn btn-outline-secondary btn-sm mb-3" id="addQuestion">+ Add question</button>

            <hr>
            <button type="submit" class="btn btn-primary">Create Quiz</button>
            <a href="{{ route('instructor.courses.edit', $course) }}" class="btn btn-outline-secondary">Cancel</a>
        </form>
    </div>
</div>

@push('scripts')
<script>
    let qIndex = 3;
    function updateTotalPoints() {
        let total = 0;
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
        if (e.target.classList.contains('q-type')) {
            toggleQuestionType(e.target.closest('.question-block'));
        }
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
