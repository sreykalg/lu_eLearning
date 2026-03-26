@extends('layouts.instructor-inner')

@section('content')
<div class="mb-4">
    <h1 class="h3 fw-bold mb-1">Create Course</h1>
</div>

<div>
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <form action="{{ route('instructor.courses.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @error('grading')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                    <div class="mb-3">
                        <label class="form-label">Title</label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required>
                        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="4">{{ old('description') }}</textarea>
                        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Level</label>
                        <select name="level" class="form-select @error('level') is-invalid @enderror" required>
                            <option value="beginner" {{ old('level') === 'beginner' ? 'selected' : '' }}>Beginner</option>
                            <option value="intermediate" {{ old('level') === 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                            <option value="advanced" {{ old('level') === 'advanced' ? 'selected' : '' }}>Advanced</option>
                        </select>
                        @error('level')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Thumbnail (optional)</label>
                        <input type="file" name="thumbnail" class="form-control @error('thumbnail') is-invalid @enderror" accept="image/*">
                        @error('thumbnail')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="border rounded p-3 mb-3">
                        <h6 class="fw-bold mb-3">Grading Setup (must total 100)</h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Quiz</label>
                                <select name="quiz_weight" class="form-select">
                                    <option value="10" {{ old('quiz_weight', 20) == 10 ? 'selected' : '' }}>10</option>
                                    <option value="20" {{ old('quiz_weight', 20) == 20 ? 'selected' : '' }}>20</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Assignment</label>
                                <select name="assignment_weight" class="form-select">
                                    <option value="10" {{ old('assignment_weight', 20) == 10 ? 'selected' : '' }}>10</option>
                                    <option value="20" {{ old('assignment_weight', 20) == 20 ? 'selected' : '' }}>20</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Midterm</label>
                                <select name="midterm_weight" class="form-select">
                                    <option value="30" {{ old('midterm_weight', 30) == 30 ? 'selected' : '' }}>30</option>
                                    <option value="40" {{ old('midterm_weight', 30) == 40 ? 'selected' : '' }}>40</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Final</label>
                                <select name="final_weight" class="form-select">
                                    <option value="30" {{ old('final_weight', 30) == 30 ? 'selected' : '' }}>30</option>
                                    <option value="40" {{ old('final_weight', 30) == 40 ? 'selected' : '' }}>40</option>
                                </select>
                            </div>
                        </div>
                        <div id="gradingTotalMessage" class="small mt-3"></div>
                    </div>
                    <button type="submit" class="btn btn-lu-primary">Create Course</button>
                    <a href="{{ route('instructor.courses.index') }}" class="btn btn-outline-secondary">Cancel</a>
                </form>
            </div>
        </div>
</div>
@push('scripts')
<script>
    (function () {
        const form = document.querySelector('form[action="{{ route('instructor.courses.store') }}"]');
        if (!form) return;
        const fields = ['quiz_weight', 'assignment_weight', 'midterm_weight', 'final_weight']
            .map((name) => form.querySelector(`[name="${name}"]`));
        const msg = document.getElementById('gradingTotalMessage');
        function updateTotal() {
            const total = fields.reduce((sum, el) => sum + Number(el?.value || 0), 0);
            if (total > 100) {
                msg.className = 'small mt-3 text-danger';
                msg.textContent = 'Cannot choose this combination, it is over 100 in total.';
            } else if (total < 100) {
                msg.className = 'small mt-3 text-warning';
                msg.textContent = 'Total is below 100. Please adjust to exactly 100.';
            } else {
                msg.className = 'small mt-3 text-success';
                msg.textContent = 'Perfect. Total is 100.';
            }
        }
        fields.forEach((el) => el?.addEventListener('change', updateTotal));
        form.addEventListener('submit', function (e) {
            const total = fields.reduce((sum, el) => sum + Number(el?.value || 0), 0);
            if (total !== 100) {
                e.preventDefault();
                msg.className = 'small mt-3 text-danger';
                msg.textContent = total > 100
                    ? 'Cannot choose this combination, it is over 100 in total.'
                    : 'Total is below 100. Please adjust to exactly 100.';
            }
        });
        updateTotal();
    })();
</script>
@endpush
@endsection
