@php
$layout = auth()->user()->isStudent()
    ? 'layouts.student-inner'
    : (auth()->user()->isHeadOfDept() ? 'layouts.hod-inner' : 'layouts.instructor-inner');
@endphp
@extends($layout)

@push('styles')
<style>
    .lsn-hero {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 55%, #334155 100%);
        border-radius: 1rem;
        padding: 1.2rem 1.3rem;
        color: #fff;
        margin-bottom: 1.2rem;
        box-shadow: 0 12px 36px rgba(15, 23, 42, 0.18);
    }
    .lsn-back {
        color: rgba(255, 255, 255, 0.86);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        font-size: 0.84rem;
        font-weight: 700;
    }
    .lsn-back:hover { color: #fff; }
    .lsn-title { margin: 0.55rem 0 0; font-size: 1.75rem; font-weight: 800; letter-spacing: -0.02em; color: #fff; }
    .lsn-subtitle { margin: 0.35rem 0 0; color: rgba(255,255,255,0.84); font-size: 0.9rem; }
    .lesson-card {
        border: 1px solid #e2e8f0;
        border-radius: 1rem;
        overflow: hidden;
        background: #fff;
        box-shadow: 0 4px 24px rgba(15, 23, 42, 0.06);
    }
    .lsn-video-wrap { background: #020617; }
    .lesson-content-list { max-height: 380px; overflow-y: auto; }
    .lesson-content-item { display: flex; align-items: center; gap: 0.75rem; padding: 0.78rem 1rem; text-decoration: none; color: #334155; border-bottom: 1px solid #f1f5f9; transition: background 0.15s; }
    .lesson-content-item:hover { background: #f8fafc; }
    .lesson-content-item.current { background: #0f172a; color: #fff; }
    .lesson-content-item.current .num { background: #fff; color: #0f172a; }
    .lesson-content-item .num { width: 28px; height: 28px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.75rem; flex-shrink: 0; }
    .lesson-content-item .num.done { background: #10b981; color: #fff; }
    .lesson-content-item .num.todo { background: #e2e8f0; color: #334155; }
    .lesson-content-item.current .num.todo { background: #fff; color: #0f172a; }
    .lsn-notes-head {
        padding: 1rem 1.15rem;
        border-bottom: 1px solid #f1f5f9;
        background: linear-gradient(180deg, #fff 0%, #fafbfc 100%);
    }
    .lsn-notes-head h5 { margin: 0; font-weight: 800; color: #0f172a; }
    .lsn-notes-body { padding: 1rem 1.15rem 1.2rem; }
    .lsn-attachments-list a { color: #0f172a; }
    .lsn-attachments-list a:hover { color: #1d4ed8; }
    .video-quiz-timeline {
        inset: 0;
        z-index: 20;
        pointer-events: none;
    }
    .video-quiz-track {
        position: absolute;
        left: 12px;
        right: 12px;
        /* Native controls can cover overlays near the bar; keep marker just above it */
        bottom: 34px;
        height: 2px;
        background: transparent;
    }
    .video-quiz-marker {
        width: 11px;
        height: 11px;
        background: #fbbf24;
        border: 1px solid rgba(15, 23, 42, 0.95);
        box-shadow: 0 0 0 2px rgba(255, 255, 255, 0.55), 0 2px 6px rgba(0, 0, 0, 0.45);
        top: 50%;
        transform: translate(-50%, -50%);
        z-index: 50;
        opacity: 1;
    }
</style>
@endpush

@section('content')
<div class="lsn-hero">
    <a href="{{ route('courses.show', $course) }}" class="lsn-back">
        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        {{ $course->title }}
    </a>
    <h1 class="lsn-title">{{ $lesson->title }}</h1>
    <p class="lsn-subtitle">Watch the lesson, complete in-video quiz checks, and continue your course progression.</p>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="lesson-card position-relative">
            <div class="lsn-video-wrap ratio ratio-16x9 d-flex align-items-center justify-content-center position-relative">
                @if ($lesson->video_url)
                    @php
                        $videoPath = strtolower((string) (parse_url($lesson->video_url, PHP_URL_PATH) ?: $lesson->video_url));
                        $videoMime = Str::endsWith($videoPath, '.webm') ? 'video/webm' : 'video/mp4';
                    @endphp
                    <video id="lesson-video" class="w-100 h-100" controls preload="metadata"
                           data-lesson-id="{{ $lesson->id }}" data-duration="{{ $lesson->video_duration ?? 0 }}">
                        <source src="{{ $lesson->video_url }}" type="{{ $videoMime }}">
                        @if($lesson->subtitle_url)
                            <track kind="subtitles" src="{{ $lesson->subtitle_url }}" srclang="en" label="English" default>
                        @endif
                    </video>
                    @if($lesson->videoQuizzes->isNotEmpty())
                        <div id="video-quiz-overlay" class="position-absolute top-0 start-0 end-0 bottom-0 d-none flex-column align-items-center justify-content-center p-4" style="background: rgba(0,0,0,0.85); z-index: 10;">
                            <div class="bg-white rounded-3 shadow-lg p-4 w-100" style="max-width: 500px;">
                                <h5 class="fw-bold mb-3" id="quiz-question"></h5>
                                <div id="quiz-options" class="d-flex flex-column gap-2 mb-3"></div>
                                <div id="quiz-feedback" class="small mb-2 d-none"></div>
                                <div class="d-flex justify-content-end">
                                    <button type="button" id="quiz-submit" class="btn btn-sm" style="background:#0f172a;color:#fff;">Submit</button>
                                </div>
                            </div>
                        </div>
                        <div id="video-quiz-timeline" class="video-quiz-timeline position-absolute">
                            <div class="video-quiz-track position-relative overflow-visible">
                                <div id="video-progress-fill" class="position-absolute top-0 start-0 bottom-0 rounded" style="background: transparent; width: 0%;"></div>
                                @foreach($lesson->videoQuizzes as $vq)
                                    <span class="quiz-marker video-quiz-marker position-absolute rounded-circle" style="left: 0%;" data-seconds="{{ $vq->timestamp_seconds }}" title="Quiz at {{ gmdate('i:s', $vq->timestamp_seconds) }}"></span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @else
                    <div class="text-white-50 text-center">
                        <svg width="64" height="64" fill="currentColor" viewBox="0 0 24 24"><path d="M4 6h16v12H4V6zm2 2v8l6-4 6 4V8H6z"/></svg>
                        <p class="mt-2 mb-0">No video available</p>
                    </div>
                @endif
            </div>
            @if ($lesson->video_url && auth()->check())
                <div id="lesson-finished-actions" class="p-3 border-top d-none" style="background: #f0fdf4;">
                    <p class="small text-success mb-2">You've finished watching this lesson. Click Finish to earn 1 point!</p>
                    <div class="d-flex gap-2">
                        <a href="{{ route('courses.show', $course) }}" class="btn btn-outline-secondary btn-sm">← Back</a>
                        <button type="button" id="lesson-finish-btn" class="btn btn-sm" style="background:#0f172a;color:#fff;border:none;">Finish & earn point</button>
                    </div>
                </div>
            @endif
        </div>
        @if ($lesson->content || $lesson->attachments->isNotEmpty())
            <div class="lesson-card mt-4">
                <div class="lsn-notes-head">
                    <h5>Lesson Notes</h5>
                </div>
                <div class="lsn-notes-body">
                    @if ($lesson->content)
                        <p class="text-muted mb-0">{{ $lesson->content }}</p>
                    @endif
                    @if ($lesson->attachments->isNotEmpty())
                        <div class="mt-3 pt-3 border-top">
                            <p class="small fw-semibold mb-2 text-secondary">Attachments</p>
                            <ul class="list-unstyled mb-0 lsn-attachments-list">
                                @foreach ($lesson->attachments as $att)
                                    <li class="mb-2">
                                        <a href="{{ route('lesson-attachments.download', $att) }}" class="d-inline-flex align-items-center gap-2 text-decoration-none">
                                            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                            {{ $att->original_name }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>
    <div class="col-lg-4">
        <div class="lesson-card sticky-top">
            <div class="p-3 border-bottom" style="background: #f9fafb;">
                <h5 class="mb-0 fw-semibold">Course Content</h5>
            </div>
            <div class="lesson-content-list">
                @foreach ($course->lessons as $l)
                    @php $isCurrent = $l->id === $lesson->id; $p = $l->getProgressFor(auth()->user()); $isCompleted = $p?->completed ?? false; @endphp
                    <a href="{{ route('lessons.show', [$course, $l]) }}"
                       class="lesson-content-item {{ $isCurrent ? 'current' : '' }}">
                        <span class="num {{ $isCompleted ? 'done' : 'todo' }}">
                            @if ($isCompleted)
                                <svg width="14" height="14" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/></svg>
                            @else
                                {{ $loop->iteration }}
                            @endif
                        </span>
                        <span class="flex-grow-1 text-truncate fw-medium">{{ $l->title }}</span>
                    </a>
                @endforeach
            </div>
            <div class="p-3 border-top d-flex gap-2" style="background: #f9fafb;">
                @if ($prevLesson)
                    <a href="{{ route('lessons.show', [$course, $prevLesson]) }}" class="btn btn-outline-secondary btn-sm flex-grow-1">&larr; Previous</a>
                @else
                    <span class="flex-grow-1"></span>
                @endif
                @if ($nextLesson)
                    <a href="{{ route('lessons.show', [$course, $nextLesson]) }}" class="btn btn-sm flex-grow-1" style="background:#0f172a;color:#fff;border:none;">Next &rarr;</a>
                @else
                    <span class="flex-grow-1"></span>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var video = document.getElementById('lesson-video');
        if (!video || !video.dataset.lessonId) return;
        var lessonId = video.dataset.lessonId, lastSent = 0;
        var videoQuizzes = @json($lesson->videoQuizzes);
        var passedQuizzes = {};
        var currentQuiz = null;

        function send(sec, done) {
            if (sec <= lastSent && !done) return;
            lastSent = sec;
            fetch('{{ route("lessons.progress") }}', {
                method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                body: JSON.stringify({ lesson_id: lessonId, watched_seconds: Math.floor(sec), completed: done })
            });
        }

        function checkQuizzes() {
            if (videoQuizzes.length && !currentQuiz) {
                var t = Math.floor(video.currentTime);
                for (var i = 0; i < videoQuizzes.length; i++) {
                    var q = videoQuizzes[i];
                    if (passedQuizzes[q.id]) continue;
                    if (t >= q.timestamp_seconds) {
                        video.pause();
                        currentQuiz = q;
                        showQuiz(q);
                        break;
                    }
                }
            }
        }

        function showQuiz(q) {
            var overlay = document.getElementById('video-quiz-overlay');
            if (!overlay) return;
            document.getElementById('quiz-question').textContent = q.question;
            var opts = document.getElementById('quiz-options');
            opts.innerHTML = '';
            var correctIdx = (q.options || []).findIndex(function(o) { return o.is_correct; });
            (q.options || []).forEach(function(o, i) {
                var lb = document.createElement('label');
                lb.className = 'd-flex align-items-center gap-2 p-2 rounded border cursor-pointer';
                lb.style.cursor = 'pointer';
                var rb = document.createElement('input');
                rb.type = 'radio';
                rb.name = 'quiz_ans';
                rb.value = i;
                lb.appendChild(rb);
                lb.appendChild(document.createTextNode(o.text));
                lb.addEventListener('click', function() { rb.checked = true; });
                opts.appendChild(lb);
            });
            document.getElementById('quiz-feedback').classList.add('d-none');
            overlay.classList.remove('d-none');
            overlay.classList.add('d-flex');
        }

        function hideQuiz() {
            var overlay = document.getElementById('video-quiz-overlay');
            if (overlay) {
                overlay.classList.add('d-none');
                overlay.classList.remove('d-flex');
            }
            currentQuiz = null;
            video.play();
        }

        var timeline = document.getElementById('video-quiz-timeline');
        var progressFill = document.getElementById('video-progress-fill');
        function updateTimeline() {
            var dur = video.duration;
            if (!dur || !isFinite(dur)) return;
            if (timeline) timeline.classList.add('d-block');
            if (progressFill) progressFill.style.width = (video.currentTime / dur * 100) + '%';
            document.querySelectorAll('.quiz-marker').forEach(function(m) {
                var sec = parseInt(m.dataset.seconds || 0);
                m.style.left = (sec / dur * 100) + '%';
            });
        }
        video.addEventListener('loadedmetadata', updateTimeline);
        video.addEventListener('canplay', updateTimeline);
        video.addEventListener('durationchange', updateTimeline);
        video.addEventListener('timeupdate', function() {
            var dur = video.duration;
            if (timeline && !timeline.classList.contains('d-block')) updateTimeline();
            if (progressFill && dur && isFinite(dur)) progressFill.style.width = (video.currentTime / dur * 100) + '%';
        });

        var quizSubmit = document.getElementById('quiz-submit');
        if (quizSubmit) quizSubmit.addEventListener('click', function() {
            if (!currentQuiz) return;
            var selected = document.querySelector('input[name="quiz_ans"]:checked');
            var feedback = document.getElementById('quiz-feedback');
            feedback.classList.remove('d-none');
            var correctIdx = (currentQuiz.options || []).findIndex(function(o) { return o.is_correct; });
            if (!selected || parseInt(selected.value) !== correctIdx) {
                feedback.textContent = 'Incorrect. Try again.';
                feedback.className = 'small mb-2 text-danger';
                return;
            }
            feedback.textContent = 'Correct!';
            feedback.className = 'small mb-2 text-success';
            passedQuizzes[currentQuiz.id] = true;
            setTimeout(hideQuiz, 600);
        });
        video.addEventListener('timeupdate', function() {
            var d = parseInt(video.dataset.duration || 0);
            send(video.currentTime, d > 0 && video.currentTime >= d * 0.9);
            checkQuizzes();
            var dur = video.duration;
            if (dur && isFinite(dur) && video.currentTime >= dur - 1) {
                document.getElementById('lesson-finished-actions')?.classList.remove('d-none');
            }
        });
        video.addEventListener('ended', function() {
            document.getElementById('lesson-finished-actions')?.classList.remove('d-none');
        });
        document.getElementById('lesson-finish-btn')?.addEventListener('click', function() {
            var btn = this;
            btn.disabled = true;
            btn.textContent = 'Saving...';
            fetch('{{ route("lessons.progress") }}', {
                method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                body: JSON.stringify({ lesson_id: lessonId, watched_seconds: Math.floor(video.duration || 0), completed: true })
            }).then(function() {
                window.location.href = '{{ $nextLesson ? route("lessons.show", [$course, $nextLesson]) : route("courses.show", $course) }}';
            }).catch(function() {
                btn.disabled = false;
                btn.textContent = 'Finish & earn point';
            });
        });

        // Ensure markers show even if metadata loaded before listeners attached.
        setTimeout(updateTimeline, 0);
    });
</script>
@endpush
@endsection
