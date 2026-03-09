@extends('layouts.instructor-inner')

@section('content')
<div class="mb-4">
    <h1 class="h3 fw-bold mb-1">Add Quiz to {{ $course->title }}</h1>
</div>

<div>
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <form action="{{ route('instructor.quizzes.store', $course) }}" method="post" id="quizForm">
                    @csrf
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
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Duration (minutes, optional)</label>
                            <input type="number" name="duration_minutes" class="form-control" min="1" value="{{ old('duration_minutes') }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Passing score (%)</label>
                            <input type="number" name="passing_score" class="form-control" min="0" max="100" value="{{ old('passing_score', 70) }}" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Max attempts (optional)</label>
                            <input type="number" name="max_attempts" class="form-control" min="1" value="{{ old('max_attempts') }}">
                        </div>
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" name="is_required" value="1" class="form-check-input" id="is_required" {{ old('is_required') ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_required">Required to complete course</label>
                    </div>

                    <hr>
                    <h5 class="mb-3">Questions</h5>
                    <div id="questionsContainer">
                        @for($i = 0; $i < 3; $i++)
                            <div class="card mb-3 question-block">
                                <div class="card-body">
                                    <div class="mb-2">
                                        <label class="form-label">Question {{ $i + 1 }}</label>
                                        <input type="text" name="questions[{{ $i }}][question]" class="form-control" placeholder="Question text">
                                    </div>
                                    <label class="form-label small">Options (check correct)</label>
                                    @foreach(range(0,3) as $j)
                                        <div class="input-group input-group-sm mb-1">
                                            <span class="input-group-text">
                                                <input type="radio" name="questions[{{ $i }}][correct]" value="{{ $j }}">
                                            </span>
                                            <input type="text" name="questions[{{ $i }}][options][{{ $j }}][text]" class="form-control" placeholder="Option {{ $j+1 }}">
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endfor
                    </div>
                    <button type="button" class="btn btn-outline-secondary btn-sm mb-3" id="addQuestion">+ Add question</button>

                    <hr>
                    <button type="submit" class="btn btn-lu-primary">Create Quiz</button>
                    <a href="{{ route('instructor.courses.edit', $course) }}" class="btn btn-outline-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        let qIndex = 3;
        document.getElementById('addQuestion').addEventListener('click', function() {
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
