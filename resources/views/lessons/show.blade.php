@php
$layout = auth()->user()->isStudent()
    ? 'layouts.student-inner'
    : (auth()->user()->isHeadOfDept() ? 'layouts.hod-inner' : 'layouts.instructor-inner');
@endphp
@extends($layout)

@push('styles')
<style>
    .lesson-back { color: #0f172a; text-decoration: none; font-size: 0.875rem; }
    .lesson-back:hover { color: #1e293b; }
    .lesson-card { border: 1px solid #e5e7eb; border-radius: 0.5rem; overflow: hidden; background: #fff; }
    .lesson-content-list { max-height: 350px; overflow-y: auto; }
    .lesson-content-item { display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem 1rem; text-decoration: none; color: #374151; border-bottom: 1px solid #f3f4f6; transition: background 0.15s; }
    .lesson-content-item:hover { background: #f9fafb; }
    .lesson-content-item.current { background: #0f172a; color: #fff; }
    .lesson-content-item.current .num { background: #fff; color: #0f172a; }
    .lesson-content-item .num { width: 28px; height: 28px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.75rem; flex-shrink: 0; }
    .lesson-content-item .num.done { background: #10b981; color: #fff; }
    .lesson-content-item .num.todo { background: #e5e7eb; color: #374151; }
    .lesson-content-item.current .num.todo { background: #fff; color: #0f172a; }
</style>
@endpush

@section('content')
<div class="mb-4">
    <a href="{{ route('courses.show', $course) }}" class="lesson-back d-inline-block mb-2">&larr; {{ $course->title }}</a>
    <h1 class="h3 fw-bold mb-0" style="color: #0f172a;">{{ $lesson->title }}</h1>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="lesson-card shadow-sm position-relative">
            <div class="ratio ratio-16x9 bg-dark d-flex align-items-center justify-content-center position-relative">
                @if ($lesson->video_url)
                    <video id="lesson-video" class="w-100 h-100" controls preload="metadata"
                           data-lesson-id="{{ $lesson->id }}" data-duration="{{ $lesson->video_duration ?? 0 }}">
                        <source src="{{ $lesson->video_url }}" type="video/mp4">
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
                                <button type="button" id="quiz-submit" class="btn btn-sm" style="background:#0f172a;color:#fff;">Submit</button>
                            </div>
                        </div>
                        <div id="video-quiz-timeline" class="position-absolute start-0 end-0 d-none align-items-center" style="bottom: 0; height: 20px; z-index: 5; padding: 0 12px 4px; pointer-events: none;">
                            <div class="flex-grow-1 position-relative rounded overflow-visible" style="height: 4px; background: rgba(255,255,255,0.4);">
                                <div id="video-progress-fill" class="position-absolute top-0 start-0 bottom-0 rounded" style="background: rgba(255,255,255,0.9); width: 0%;"></div>
                                @foreach($lesson->videoQuizzes as $vq)
                                    <span class="quiz-marker position-absolute top-50 translate-middle-y rounded-circle" style="width: 10px; height: 10px; background: #fbbf24; left: 0%; margin-left: -5px;" data-seconds="{{ $vq->timestamp_seconds }}" title="Quiz at {{ gmdate('i:s', $vq->timestamp_seconds) }}"></span>
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
            <div class="lesson-card shadow-sm mt-4">
                <div class="p-4">
                    <h5 class="fw-semibold mb-2">Lesson Notes</h5>
                    @if ($lesson->content)
                        <p class="text-muted mb-0">{{ $lesson->content }}</p>
                    @endif
                    @if ($lesson->attachments->isNotEmpty())
                        <div class="mt-3 pt-3 border-top">
                            <p class="small fw-semibold mb-2 text-secondary">Attachments</p>
                            <ul class="list-unstyled mb-0">
                                @foreach ($lesson->attachments as $att)
                                    <li class="mb-2">
                                        <a href="{{ route('lesson-attachments.download', $att) }}" class="d-inline-flex align-items-center gap-2 text-decoration-none" style="color: #0f172a;">
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
        <div class="lesson-card shadow-sm sticky-top">
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
            if (timeline) {
                timeline.classList.remove('d-none');
                timeline.classList.add('d-flex');
            }
            if (progressFill) progressFill.style.width = (video.currentTime / dur * 100) + '%';
            document.querySelectorAll('.quiz-marker').forEach(function(m) {
                var sec = parseInt(m.dataset.seconds || 0);
                m.style.left = (sec / dur * 100) + '%';
            });
        }
        video.addEventListener('loadedmetadata', updateTimeline);
        video.addEventListener('durationchange', updateTimeline);
        video.addEventListener('timeupdate', function() {
            var dur = video.duration;
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
    });
</script>
@endpush
@endsection
