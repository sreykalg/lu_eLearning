@php $courses = $courses ?? auth()->user()->courses()->orderBy('title')->get(); @endphp
<div class="cb-sidebar">
    <div class="dropdown mb-3">
        <button class="cb-dropdown w-100 d-flex align-items-center justify-content-between" type="button" data-bs-toggle="dropdown">
            <span class="text-truncate">{{ $course->title }}</span>
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
        </button>
        <ul class="dropdown-menu w-100">
            @foreach($courses as $c)
                <li><a class="dropdown-item {{ $c->id === $course->id ? 'active' : '' }}" href="{{ route('instructor.courses.edit', $c) }}">{{ $c->title }}</a></li>
            @endforeach
        </ul>
    </div>
    <button type="button" class="cb-add-module w-100 mb-3" data-bs-toggle="modal" data-bs-target="#addModuleModal">+ Add Module</button>
    <div class="cb-tree">
        @foreach($course->modules as $mod)
            <div class="cb-module">
                <div class="cb-module-header">
                    <span class="cb-drag">⋮⋮</span>
                    <span class="cb-module-title fw-bold">{{ $mod->title }}</span>
                </div>
                <div class="cb-lessons ms-3">
                    @foreach($mod->lessons as $l)
                        <a href="{{ route('instructor.lessons.edit', [$course, $l]) }}" class="cb-item {{ isset($lesson) && $lesson->id === $l->id ? 'active' : '' }}">
                            <span class="cb-drag">⋮⋮</span>
                            @if($l->video_url)
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                            @else
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                            @endif
                            <span class="cb-label">{{ Str::limit($l->title, 32) }}</span>
                        </a>
                    @endforeach
                    @foreach($mod->quizzes as $q)
                        <a href="{{ route('instructor.quizzes.edit', [$course, $q]) }}" class="cb-item {{ isset($quiz) && $quiz->id === $q->id ? 'active' : '' }}">
                            <span class="cb-drag">⋮⋮</span>
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            <span class="cb-label">{{ Str::limit($q->title, 32) }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        @endforeach
        @php $uLessons = $course->lessons->whereNull('module_id'); $uQuizzes = $course->quizzes->whereNull('module_id'); @endphp
        @if($uLessons->isNotEmpty() || $uQuizzes->isNotEmpty())
            <div class="cb-ungrouped mt-2 pt-2 border-top">
                @foreach($uLessons as $l)
                    <a href="{{ route('instructor.lessons.edit', [$course, $l]) }}" class="cb-item {{ isset($lesson) && $lesson->id === $l->id ? 'active' : '' }}">
                        <span class="cb-drag">⋮⋮</span>
                        @if($l->video_url)
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                        @else
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        @endif
                        <span class="cb-label">{{ Str::limit($l->title, 32) }}</span>
                    </a>
                @endforeach
                @foreach($uQuizzes as $q)
                    <a href="{{ route('instructor.quizzes.edit', [$course, $q]) }}" class="cb-item {{ isset($quiz) && $quiz->id === $q->id ? 'active' : '' }}">
                        <span class="cb-drag">⋮⋮</span>
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <span class="cb-label">{{ Str::limit($q->title, 32) }}</span>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
    <div class="cb-actions mt-3 pt-3 border-top">
        <a href="{{ route('instructor.lessons.create', $course) }}" class="btn btn-outline-secondary btn-sm w-100 mb-1">+ Add Lesson</a>
        <a href="{{ route('instructor.quizzes.create', $course) }}" class="btn btn-outline-secondary btn-sm w-100">+ Add Quiz</a>
    </div>
</div>

{{-- Add Module modal --}}
<div class="modal fade" id="addModuleModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('instructor.modules.store', $course) }}" method="post">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add Module</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <label class="form-label">Module title</label>
                    <input type="text" name="title" class="form-control" placeholder="e.g. Computing Fundamentals" required>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Module</button>
                </div>
            </form>
        </div>
    </div>
</div>
