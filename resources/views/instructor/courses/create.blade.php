@extends('layouts.instructor-inner')

@push('styles')
<style>
    .ccr-hero {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 55%, #334155 100%);
        border-radius: 1rem;
        padding: 1.25rem 1.4rem;
        color: #fff;
        margin-bottom: 1rem;
    }
    .ccr-hero-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        flex-wrap: wrap;
    }
    .ccr-hero-left { display: flex; align-items: center; gap: 0.9rem; }
    .ccr-hero-icon {
        width: 46px;
        height: 46px;
        border-radius: 0.8rem;
        background: rgba(255,255,255,0.12);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .ccr-title { margin: 0; font-size: 1.35rem; font-weight: 800; letter-spacing: -0.02em; }
    .ccr-sub { margin: 0.3rem 0 0; color: rgba(255,255,255,0.84); font-size: 0.9rem; }

    .ccr-flow {
        display: flex;
        gap: 0.45rem;
        flex-wrap: wrap;
        margin-bottom: 1rem;
    }
    .ccr-flow-chip {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        font-size: 0.76rem;
        font-weight: 700;
        border-radius: 9999px;
        padding: 0.28rem 0.62rem;
        border: 1px solid #e2e8f0;
        color: #334155;
        background: #fff;
    }
    .ccr-flow-chip.active {
        background: #eef2ff;
        border-color: #c7d2fe;
        color: #3730a3;
    }

    .ccr-form {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 0.95rem;
        box-shadow: 0 4px 20px rgba(15, 23, 42, 0.05);
        padding: 1rem 1rem 1.1rem;
    }
    .ccr-section {
        padding: 0.15rem 0 1rem;
        margin-bottom: 1rem;
        border-bottom: 1px solid #e5e7eb;
    }
    .ccr-section:last-of-type { margin-bottom: 0; border-bottom: 0; }
    .ccr-section-title {
        margin: 0 0 0.75rem;
        font-size: 0.76rem;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: #64748b;
        font-weight: 700;
    }
    .ccr-form .form-label {
        font-size: 0.84rem;
        font-weight: 600;
        color: #334155;
        margin-bottom: 0.35rem;
    }
    .ccr-form .form-control,
    .ccr-form .form-select {
        border-radius: 0.65rem;
        border-color: #d1d5db;
        min-height: 42px;
    }
    .ccr-form .form-control:focus,
    .ccr-form .form-select:focus {
        border-color: #0f172a;
        box-shadow: 0 0 0 0.2rem rgba(15, 23, 42, 0.08);
    }
    .ccr-form textarea.form-control { min-height: 120px; }
    .ccr-grade-note {
        font-size: 0.78rem;
        color: #64748b;
        margin: 0 0 0.75rem;
    }
    .ccr-actions {
        display: flex;
        gap: 0.6rem;
        justify-content: flex-end;
        align-items: center;
        flex-wrap: wrap;
        padding-top: 0.9rem;
        border-top: 1px solid #f1f5f9;
        margin-top: 0.9rem;
    }
    .ccr-submit {
        border-radius: 0.65rem;
        font-weight: 700;
        padding: 0.52rem 1.1rem;
    }
</style>
@endpush

@section('content')
<div class="ccr-hero">
    <div class="ccr-hero-row">
        <div class="ccr-hero-left">
            <div class="ccr-hero-icon">
                <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
            </div>
            <div>
                <h1 class="ccr-title">Create Course</h1>
                <p class="ccr-sub">Create a draft course first, then submit it to HoD for approval from the edit page.</p>
            </div>
        </div>
    </div>
</div>

<div class="ccr-flow">
    <span class="ccr-flow-chip active">1. Create draft</span>
    <span class="ccr-flow-chip">2. Add modules/lessons</span>
    <span class="ccr-flow-chip">3. Submit to HoD</span>
    <span class="ccr-flow-chip">4. Publish after approval</span>
</div>

<div class="ccr-form">
    <form action="{{ route('instructor.courses.store') }}" method="post" enctype="multipart/form-data">
        @csrf
        @error('grading')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror

        <div class="ccr-section">
            <h2 class="ccr-section-title">Course information</h2>
            <div class="mb-3 px-1">
                        <label class="form-label">Title</label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required>
                        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3 px-1">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="4">{{ old('description') }}</textarea>
                        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="px-1">
                        <label class="form-label">Level</label>
                        <select name="level" class="form-select @error('level') is-invalid @enderror" required>
                            <option value="beginner" {{ old('level') === 'beginner' ? 'selected' : '' }}>Beginner</option>
                            <option value="intermediate" {{ old('level') === 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                            <option value="advanced" {{ old('level') === 'advanced' ? 'selected' : '' }}>Advanced</option>
                        </select>
                        @error('level')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="ccr-section">
            <h2 class="ccr-section-title">Thumbnail</h2>
            <div class="px-1">
                        <label class="form-label">Thumbnail (optional)</label>
                        <input type="file" name="thumbnail" class="form-control @error('thumbnail') is-invalid @enderror" accept="image/jpeg,image/png,image/webp,.jpg,.jpeg,.png,.webp">
                        @error('thumbnail')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <p class="small text-muted mt-2 mb-0">Use JPG, PNG, or WebP up to 4MB. Uploaded thumbnails are optimized to web-safe format for production.</p>
            </div>
        </div>

        <div class="ccr-section">
            <h2 class="ccr-section-title">Grading setup</h2>
            <p class="ccr-grade-note px-1">All components must total exactly 100.</p>
            <div class="row g-3 px-1">
                            <div class="col-md-6">
                                <label class="form-label">Quiz</label>
                                <select name="quiz_weight" class="form-select">
                                    <option value="10" {{ old('quiz_weight', 10) == 10 ? 'selected' : '' }}>10</option>
                                    <option value="20" {{ old('quiz_weight', 10) == 20 ? 'selected' : '' }}>20</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Assignment</label>
                                <select name="assignment_weight" class="form-select">
                                    <option value="10" {{ old('assignment_weight', 10) == 10 ? 'selected' : '' }}>10</option>
                                    <option value="20" {{ old('assignment_weight', 10) == 20 ? 'selected' : '' }}>20</option>
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
                                    <option value="30" {{ old('final_weight', 40) == 30 ? 'selected' : '' }}>30</option>
                                    <option value="40" {{ old('final_weight', 40) == 40 ? 'selected' : '' }}>40</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Attendance</label>
                                <select class="form-select" disabled>
                                    <option value="10" selected>10</option>
                                </select>
                                <input type="hidden" name="attendance_weight" value="10">
                            </div>
            </div>
            <div id="gradingTotalMessage" class="small mt-3 px-1"></div>
        </div>

        <div class="ccr-actions">
            <a href="{{ route('instructor.courses.index') }}" class="btn btn-outline-secondary">Cancel</a>
            <button type="submit" class="btn btn-lu-primary ccr-submit">Create Course</button>
        </div>
    </form>
</div>
@push('scripts')
<script>
    (function () {
        const form = document.querySelector('form[action="{{ route('instructor.courses.store') }}"]');
        if (!form) return;
        const fields = ['quiz_weight', 'assignment_weight', 'midterm_weight', 'final_weight', 'attendance_weight']
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
