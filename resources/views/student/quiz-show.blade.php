@php
$layout = auth()->user()->isStudent()
    ? 'layouts.student-inner'
    : (auth()->user()->isHeadOfDept() ? 'layouts.hod-inner' : 'layouts.instructor-inner');
@endphp
@extends($layout)

@push('styles')
<style>
    .page-hero {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 55%, #334155 100%);
        border-radius: 1rem;
        padding: 1.25rem 1.4rem;
        color: #fff;
        margin-bottom: 1rem;
        box-shadow: 0 12px 34px rgba(15, 23, 42, 0.18);
    }
    .page-hero .hero-row { display: flex; align-items: center; justify-content: space-between; gap: 0.75rem; flex-wrap: wrap; }
    .page-hero .hero-left { display: flex; align-items: center; gap: 0.9rem; }
    .page-hero .hero-icon { width: 44px; height: 44px; border-radius: 0.75rem; background: rgba(255,255,255,0.12); display: flex; align-items: center; justify-content: center; }
    .page-hero .hero-title { margin: 0; font-weight: 800; letter-spacing: -0.015em; }
    .page-hero .hero-subtitle { margin: 0.2rem 0 0; color: rgba(255,255,255,0.8); font-size: 0.9rem; }
    .page-hero .back-link { color: rgba(255,255,255,0.85); text-decoration: none; font-size: 0.85rem; display: inline-flex; align-items: center; gap: 0.3rem; margin-bottom: 0.65rem; }
    .page-hero .back-link:hover { color: #fff; }
    .quiz-shell { background: #fff; border: 1px solid #e2e8f0; border-radius: 1rem; overflow: hidden; box-shadow: 0 4px 22px rgba(15, 23, 42, 0.06); }
    .quiz-shell .quiz-intro { padding: 1rem 1.2rem; border-bottom: 1px solid #f1f5f9; background: linear-gradient(180deg, #fff 0%, #fafbfc 100%); }
    .quiz-shell .quiz-content { padding: 1rem 1.2rem 1.2rem; }
    .question-card {
        border: 0;
        border-radius: 0;
        background: transparent;
        padding: 0 0 1rem;
        margin-bottom: 1rem;
        border-bottom: 1px solid #f1f5f9;
    }
    .question-title { font-weight: 700; color: #0f172a; margin-bottom: 0.65rem; }
    .question-pts { color: #64748b; font-weight: 500; font-size: 0.82rem; }
    .answer-label {
        display: block;
        font-size: 0.74rem;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: #64748b;
        font-weight: 700;
        margin-bottom: 0.45rem;
    }
    .answer-input {
        display: block;
        width: 100%;
        border: 1px solid #cbd5e1;
        border-radius: 0.6rem;
        padding: 0.72rem 0.82rem;
        font-size: 0.94rem;
        color: #0f172a;
        background: #fff;
        transition: border-color .15s ease, box-shadow .15s ease;
        resize: vertical;
        min-height: 120px;
    }
    .answer-input:focus {
        border-color: #0f172a;
        box-shadow: 0 0 0 3px rgba(15, 23, 42, 0.08);
        outline: none;
    }
    .answer-hint {
        margin-top: 0.45rem;
        font-size: 0.76rem;
        color: #64748b;
    }
    .option-item { border: 1px solid #e2e8f0; border-radius: 0.6rem; padding: 0.55rem 0.65rem; margin-bottom: 0.5rem; background: #f8fafc; }
    .option-item:hover { background: #f1f5f9; border-color: #cbd5e1; }
    .quiz-submit-row { border-top: 1px solid #e5e7eb; margin-top: 0.8rem; padding-top: 0.9rem; display: flex; justify-content: space-between; align-items: center; gap: 0.75rem; flex-wrap: wrap; }
    .quiz-submit-btn { background: #0f172a; color: #fff; border-radius: 0.6rem; padding: 0.5rem 1rem; font-weight: 600; border: none; }
    .quiz-submit-btn:hover { background: #1e293b; color: #fff; }
    .attempts-card { border: 1px solid #e5e7eb; border-radius: 0.9rem; background: #fff; box-shadow: 0 4px 18px rgba(15, 23, 42, 0.05); }
    .attempts-list li { padding: 0.5rem 0.25rem; border-bottom: 1px solid #f1f5f9; }
    .attempts-list li:last-child { border-bottom: none; }
    .quiz-total-chip {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        border-radius: 9999px;
        padding: 0.22rem 0.55rem;
        border: 1px solid #e2e8f0;
        background: #f8fafc;
        color: #334155;
        font-size: 0.78rem;
        font-weight: 600;
    }
    .ans-file-dropzone {
        border: 2px dashed #cbd5e1;
        border-radius: 0.8rem;
        background: #f8fafc;
        padding: 0.85rem 0.9rem;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .ans-file-dropzone:hover { border-color: #334155; background: #f1f5f9; }
    .ans-file-dropzone.is-dragover {
        border-color: #0f172a;
        background: #eef2ff;
        box-shadow: 0 0 0 4px rgba(15, 23, 42, 0.08);
    }
    .ans-file-dropzone .main { color: #334155; font-size: 0.84rem; font-weight: 700; }
    .ans-file-dropzone .sub { color: #64748b; font-size: 0.76rem; margin-top: 0.15rem; }
    .ans-file-list { margin-top: 0.6rem; display: grid; gap: 0.45rem; }
    .ans-file-card {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.55rem;
        border: 1px solid #e2e8f0;
        background: #fff;
        border-radius: 0.75rem;
        padding: 0.46rem 0.62rem;
    }
    .ans-file-card-link {
        display: flex;
        align-items: center;
        gap: 0.55rem;
        min-width: 0;
        color: #0f172a;
        text-decoration: none;
    }
    .ans-file-card-link:hover { color: #0f172a; }
    .ans-file-icon {
        width: 34px;
        height: 34px;
        border-radius: 0.5rem;
        border: 1px solid #e2e8f0;
        background: #f8fafc;
        color: #475569;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .ans-file-name {
        font-size: 0.84rem;
        font-weight: 600;
        text-decoration: underline;
        text-underline-offset: 2px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .ans-file-type {
        font-size: 0.7rem;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        margin-top: 0.08rem;
    }
    .ans-file-remove {
        width: 22px;
        height: 22px;
        border-radius: 9999px;
        border: 1px solid #fecaca;
        background: #fff1f2;
        color: #b91c1c;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        line-height: 1;
        padding: 0;
    }
    .ans-file-remove:hover { background: #ffe4e6; border-color: #fca5a5; color: #991b1b; }
</style>
@endpush

@section('content')
<div class="page-hero">
    <a href="{{ route('courses.show', $course) }}" class="back-link">
        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        {{ $course->title }}
    </a>
    <div class="hero-row">
        <div class="hero-left">
            <div class="hero-icon">
                <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/></svg>
            </div>
            <div>
                <h1 class="h3 hero-title">{{ $quiz->title }}</h1>
                <p class="hero-subtitle">
                    {{ $course->title }}
                    @if($quiz->type !== 'practice')
                        · {{ ucfirst($quiz->type) }}
                    @endif
                </p>
            </div>
        </div>
    </div>
</div>

@if($attempts->isNotEmpty())
    <div class="card border-0 shadow-sm mb-3 attempts-card">
        <div class="card-body">
            <h5 class="fw-semibold mb-2">Previous attempts</h5>
            <ul class="list-unstyled mb-0 small attempts-list">
                @foreach($attempts->take(5) as $a)
                    <li class="py-1">{{ $a->submitted_at?->diffForHumans() }} — {{ $a->score }}/{{ $a->total_points }} @if($a->passed !== null) ({{ $a->passed ? 'Passed' : 'Not passed' }}) @endif</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif

<div class="quiz-shell">
    <div class="quiz-intro">
        @if($quiz->description)
            <p class="text-muted mb-2">{{ $quiz->description }}</p>
        @endif
        @php $totalPts = $quiz->total_points ?? $quiz->questions->sum('points'); @endphp
        @if($totalPts > 0)
            <span class="quiz-total-chip">
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                Total: {{ $totalPts }} points
            </span>
        @endif
    </div>
    <div class="quiz-content">
        <form method="POST" action="{{ route('student.quizzes.submit', [$course, $quiz]) }}" enctype="multipart/form-data">
            @csrf
            @foreach($quiz->questions as $index => $q)
                @php
                    $qType = $q->type ?? 'multiple_choice';
                    $opts = $q->options ?? [];
                @endphp
                <div class="question-card">
                    <p class="question-title mb-2">{{ $index + 1 }}. {{ $q->question }} <span class="question-pts">({{ (int)($q->points ?? 1) }} pt{{ (int)($q->points ?? 1) !== 1 ? 's' : '' }})</span></p>
                    @if($qType === 'multiple_choice')
                        @foreach($opts as $i => $opt)
                            <label class="d-flex align-items-center gap-2 small cursor-pointer option-item" style="cursor: pointer;">
                                <input type="radio" name="answers[{{ $q->id }}]" value="{{ $i }}" class="form-check-input">
                                <span>{{ $opt['text'] ?? '' }}</span>
                            </label>
                        @endforeach
                    @elseif($qType === 'short_answer')
                        <label class="answer-label" for="answer-{{ $q->id }}">Your answer</label>
                        <textarea id="answer-{{ $q->id }}" name="answers[{{ $q->id }}]" class="answer-input" rows="4" placeholder="Type your answer here..."></textarea>
                        <div class="answer-hint">Press Enter for a new line.</div>
                    @elseif($qType === 'file_upload')
                        <label class="answer-label">Upload answer file</label>
                        @php $promptFile = $opts['prompt_file'] ?? null; @endphp
                        @if($promptFile && !empty($promptFile['path']))
                            <div class="mb-2">
                                <a href="{{ asset('storage/' . $promptFile['path']) }}" target="_blank" class="btn btn-sm btn-outline-secondary">
                                    View question file: {{ $promptFile['name'] ?? 'Attachment' }}
                                </a>
                            </div>
                        @endif
                        <div class="ans-file-dropzone" data-file-dropzone data-qid="{{ $q->id }}" role="button" tabindex="0">
                            <div class="main">Drag & drop your answer file, or click to browse</div>
                            <div class="sub">Upload one file for this question (PDF, DOCX, PPTX, ZIP, image, etc. up to 50MB).</div>
                        </div>
                        <input type="file" class="d-none" data-file-input data-qid="{{ $q->id }}" name="answer_files[{{ $q->id }}]">
                        <div class="ans-file-list d-none" data-file-list data-qid="{{ $q->id }}"></div>
                    @else
                        <label class="answer-label" for="answer-{{ $q->id }}">Your answer</label>
                        <textarea id="answer-{{ $q->id }}" name="answers[{{ $q->id }}]" class="answer-input font-monospace" rows="8" placeholder="Write your code or answer here..."></textarea>
                        <div class="answer-hint">Press Enter for a new line.</div>
                    @endif
                </div>
            @endforeach
            @if($quiz->questions->isEmpty())
                <p class="text-muted mb-0">No questions in this quiz yet.</p>
            @else
                <div class="quiz-submit-row">
                    <span class="text-muted small">Review your answers before submitting.</span>
                    <button type="submit" class="btn quiz-submit-btn">Submit quiz</button>
                </div>
            @endif
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var form = document.querySelector('form[action*="quizzes"][action*="submit"]');
    if (!form) return;
    form.addEventListener('keydown', function (e) {
        if (e.key !== 'Enter') return;
        var t = e.target;
        if (!t) return;
        var tag = (t.tagName || '').toLowerCase();
        // Allow Enter in textareas for multiline answers.
        if (tag === 'textarea') return;
        // Prevent accidental submit by Enter on other controls.
        e.preventDefault();
    });

    var fileInputs = document.querySelectorAll('[data-file-input]');
    var getFileExt = function (name) {
        var parts = String(name || '').split('.');
        return parts.length > 1 ? parts.pop().toUpperCase() : 'FILE';
    };
    var renderFileCard = function (qid, file) {
        var list = document.querySelector('[data-file-list][data-qid="' + qid + '"]');
        var input = document.querySelector('[data-file-input][data-qid="' + qid + '"]');
        if (!list || !input || !file) return;
        list.innerHTML = '';
        list.classList.remove('d-none');
        var row = document.createElement('div');
        row.className = 'ans-file-card';
        var fileUrl = URL.createObjectURL(file);
        var link = document.createElement('a');
        link.className = 'ans-file-card-link';
        link.href = fileUrl;
        link.target = '_blank';
        link.rel = 'noopener';
        link.addEventListener('click', function (e) { e.stopPropagation(); });
        var icon = document.createElement('span');
        icon.className = 'ans-file-icon';
        icon.innerHTML = '<svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M14 2H7a2 2 0 00-2 2v16a2 2 0 002 2h10a2 2 0 002-2V8z"/><path stroke-width="2" d="M14 2v6h6"/></svg>';
        var meta = document.createElement('div');
        meta.style.minWidth = '0';
        var name = document.createElement('div');
        name.className = 'ans-file-name';
        name.textContent = file.name;
        var type = document.createElement('div');
        type.className = 'ans-file-type';
        type.textContent = getFileExt(file.name);
        meta.appendChild(name);
        meta.appendChild(type);
        link.appendChild(icon);
        link.appendChild(meta);
        var remove = document.createElement('button');
        remove.type = 'button';
        remove.className = 'ans-file-remove';
        remove.textContent = 'X';
        remove.addEventListener('click', function (e) {
            e.stopPropagation();
            input.value = '';
            list.innerHTML = '';
            list.classList.add('d-none');
        });
        row.appendChild(link);
        row.appendChild(remove);
        list.appendChild(row);
    };

    document.querySelectorAll('[data-file-dropzone]').forEach(function (dropzone) {
        var qid = dropzone.getAttribute('data-qid');
        var input = document.querySelector('[data-file-input][data-qid="' + qid + '"]');
        if (!input) return;
        var preventDefaults = function (e) {
            e.preventDefault();
            e.stopPropagation();
        };
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(function (name) {
            dropzone.addEventListener(name, preventDefaults);
        });
        ['dragenter', 'dragover'].forEach(function (name) {
            dropzone.addEventListener(name, function () { dropzone.classList.add('is-dragover'); });
        });
        ['dragleave', 'drop'].forEach(function (name) {
            dropzone.addEventListener(name, function () { dropzone.classList.remove('is-dragover'); });
        });
        dropzone.addEventListener('click', function () { input.click(); });
        dropzone.addEventListener('keydown', function (e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                input.click();
            }
        });
        dropzone.addEventListener('drop', function (e) {
            var files = e.dataTransfer && e.dataTransfer.files;
            if (!files || !files.length) return;
            var transfer = new DataTransfer();
            transfer.items.add(files[0]);
            input.files = transfer.files;
            renderFileCard(qid, files[0]);
        });
        input.addEventListener('change', function () {
            var file = input.files && input.files[0];
            if (!file) return;
            renderFileCard(qid, file);
        });
    });
});
</script>
@endpush
