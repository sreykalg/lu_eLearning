@extends('layouts.instructor-inner')

@push('styles')
<style>
    .cb-wrap { display: flex; min-height: 560px; overflow-x: hidden; background: #fff; border-radius: 0.5rem; border: 1px solid #e5e7eb; box-shadow: 0 1px 3px rgba(0,0,0,0.06); }
    .cb-sidebar { width: 380px; min-width: 380px; max-width: 380px; flex-shrink: 0; padding: 1rem 1.5rem 1rem 1rem; max-height: 85vh; overflow-y: auto; overflow-x: hidden; border-right: 1px solid #e5e7eb; border-radius: 0.5rem 0 0 0.5rem; }
    .cb-dropdown { background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 0.5rem; padding: 0.5rem 0.75rem; font-size: 0.875rem; color: #374151; }
    .cb-add-module { background: #fff; border: 1px solid #e5e7eb; border-radius: 0.5rem; padding: 0.5rem 0.75rem; font-size: 0.875rem; color: #374151; text-align: left; width: 100%; }
    .cb-add-module:hover { background: #f9fafb; }
    .cb-module-header { padding: 0.35rem 0; font-size: 0.875rem; display: flex; align-items: center; gap: 0.5rem; }
    .cb-module-title { color: #374151; }
    .cb-drag { color: #9ca3af; font-size: 0.75rem; cursor: default; pointer-events: none; }
    .cb-item { display: flex; align-items: center; gap: 0.5rem; padding: 0.5rem 0.75rem; margin: 0.15rem 0; border-radius: 0.375rem; text-decoration: none; color: #374151; font-size: 0.875rem; border-left: 3px solid transparent; }
    .cb-item:hover { background: #f3f4f6; }
    .cb-item.active { background: #0f172a; color: #fff; border-left-color: #0f172a; }
    .cb-label { flex: 1; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .cb-main { flex: 1; min-width: 0; padding: 1.5rem 2.5rem 1.5rem 2rem; overflow: auto; }
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
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0 fw-bold">Edit Quiz</h4>
            <div class="d-flex gap-2">
                <button type="submit" form="quizForm" class="btn btn-outline-secondary btn-sm">Draft</button>
                <form action="{{ route('instructor.quizzes.destroy', [$course, $quiz]) }}" method="post" class="d-inline" onsubmit="return confirm('Delete this quiz?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-secondary btn-sm text-danger">Delete</button>
                </form>
                <button type="submit" form="quizForm" class="btn btn-sm" style="background:#0f172a;color:#fff;border:none;">Publish</button>
            </div>
        </div>

        <form action="{{ route('instructor.quizzes.update', [$course, $quiz]) }}" method="post" id="quizForm">
                    @csrf
                    @method('PUT')
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
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Duration (minutes)</label>
                            <input type="number" name="duration_minutes" class="form-control" min="1" value="{{ old('duration_minutes', $quiz->duration_minutes) }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Passing score (%)</label>
                            <input type="number" name="passing_score" class="form-control" min="0" max="100" value="{{ old('passing_score', $quiz->passing_score) }}" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Max attempts</label>
                            <input type="number" name="max_attempts" class="form-control" min="1" value="{{ old('max_attempts', $quiz->max_attempts) }}">
                        </div>
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" name="is_required" value="1" class="form-check-input" id="is_required" {{ old('is_required', $quiz->is_required) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_required">Required</label>
                    </div>

                    <hr>
                    <h5 class="mb-3">Questions</h5>
                    <div id="questionsContainer">
                        @foreach($quiz->questions as $i => $q)
                            <div class="card mb-3 question-block">
                                <div class="card-body">
                                    <input type="hidden" name="questions[{{ $i }}][id]" value="{{ $q->id }}">
                                    <div class="mb-2">
                                        <label class="form-label">Question {{ $i + 1 }}</label>
                                        <input type="text" name="questions[{{ $i }}][question]" class="form-control" value="{{ old("questions.{$i}.question", $q->question) }}">
                                    </div>
                                    <label class="form-label small">Options (select correct)</label>
                                    @php $opts = $q->options ?? []; @endphp
                                    @foreach(range(0,3) as $j)
                                        @php $opt = $opts[$j] ?? []; $correctIdx = collect($opts)->search(fn($x) => $x['is_correct'] ?? false); @endphp
                                        <div class="input-group input-group-sm mb-1">
                                            <span class="input-group-text">
                                                <input type="radio" name="questions[{{ $i }}][correct]" value="{{ $j }}" {{ ($correctIdx !== false && $correctIdx == $j) ? 'checked' : '' }}>
                                            </span>
                                            <input type="text" name="questions[{{ $i }}][options][{{ $j }}][text]" class="form-control" value="{{ $opt['text'] ?? '' }}" placeholder="Option {{ $j+1 }}">
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <button type="button" class="btn btn-outline-secondary btn-sm mb-3" id="addQuestion">+ Add question</button>

                    <hr>
                    <button type="submit" class="btn btn-primary">Save Quiz</button>
                    <a href="{{ route('instructor.courses.edit', $course) }}" class="btn btn-outline-secondary">Cancel</a>
                </form>
    </div>
</div>

    @push('scripts')
    <script>
        let qIndex = {{ $quiz->questions->count() }};
        document.getElementById('addQuestion')?.addEventListener('click', function() {
            const html = `
                <div class="card mb-3 question-block">
                    <div class="card-body">
                        <div class="mb-2">
                            <label class="form-label">Question ${qIndex + 1}</label>
                            <input type="text" name="questions[${qIndex}][question]" class="form-control" placeholder="Question text">
                        </div>
                        <label class="form-label small">Options (check correct)</label>
                        ${[0,1,2,3].map(j => `
                            <div class="input-group input-group-sm mb-1">
                                <span class="input-group-text"><input type="radio" name="questions[${qIndex}][correct]" value="${j}"></span>
                                <input type="text" name="questions[${qIndex}][options][${j}][text]" class="form-control" placeholder="Option ${j+1}">
                            </div>
                        `).join('')}
                    </div>
                </div>`;
            document.getElementById('questionsContainer').insertAdjacentHTML('beforeend', html);
            qIndex++;
        });
    </script>
    @endpush
@endsection
