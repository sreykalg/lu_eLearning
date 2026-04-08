@php $courses = $courses ?? auth()->user()->courses()->orderBy('title')->get(); @endphp
<div class="cb-sidebar-panel">
    <div class="cb-sidebar-card">
        <div class="cb-sidebar-card__head">
            <div class="cb-sidebar-label">Course structure</div>
            <div class="dropdown">
                <button class="cb-course-select" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <span class="text-truncate">{{ $course->title }}</span>
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3 py-2" style="width: min(100vw - 2rem, 340px);">
                    @foreach($courses as $c)
                        <li>
                            <a class="dropdown-item rounded-2 mx-1 {{ $c->id === $course->id ? 'active fw-semibold' : '' }}" href="{{ route('instructor.courses.edit', $c) }}">{{ $c->title }}</a>
                        </li>
                    @endforeach
                </ul>
            </div>
            <button type="button" class="cb-btn-add-module" data-bs-toggle="modal" data-bs-target="#addModuleModal">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Add module
            </button>
        </div>
        <div class="cb-sidebar-card__body">
    <div class="cb-tree">
        @foreach($course->modules as $mod)
            <div class="cb-module-block">
                <div class="cb-module-header">
                    <span class="cb-drag" aria-hidden="true">⋮⋮</span>
                    <span class="cb-module-title">{{ $mod->title }}</span>
                </div>
                <div class="cb-lessons">
                    @foreach($mod->lessons as $l)
                        <a href="{{ route('instructor.lessons.edit', [$course, $l]) }}" class="cb-item {{ isset($lesson) && $lesson->id === $l->id ? 'active' : '' }}">
                            <span class="cb-drag" aria-hidden="true">⋮⋮</span>
                            <span class="cb-item-icon-wrap cb-item-icon-wrap--lesson">
                                @if($l->video_url)
                                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                @else
                                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                @endif
                            </span>
                            <span class="cb-label">{{ Str::limit($l->title, 34) }}</span>
                            @if($l->videoQuizzes->isNotEmpty())
                                <span class="cb-lesson-quiz-dot" title="Has in-video quiz" aria-label="Has in-video quiz"></span>
                            @endif
                        </a>
                    @endforeach
                    @foreach($mod->quizzes as $q)
                        <a href="{{ route('instructor.quizzes.edit', [$course, $q]) }}" class="cb-item {{ isset($quiz) && $quiz->id === $q->id ? 'active' : '' }}">
                            <span class="cb-drag" aria-hidden="true">⋮⋮</span>
                            <span class="cb-item-icon-wrap cb-item-icon-wrap--quiz">
                                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            </span>
                            <span class="cb-label">{{ Str::limit($q->title, 34) }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        @endforeach
        @php
            $uLessons = $course->lessons->whereNull('module_id');
            $uQuizzes = $course->quizzes->whereNull('module_id');
            $uAssignments = $course->assignments ?? collect();
        @endphp
        @if($uAssignments->isNotEmpty())
            <div class="cb-ungrouped">
                <div class="cb-section-label">Assignments</div>
                @foreach($uAssignments as $a)
                    <a href="{{ route('instructor.assignments.edit', [$course, $a]) }}" class="cb-item {{ isset($assignment) && $assignment->id === $a->id ? 'active' : '' }}">
                        <span class="cb-drag" aria-hidden="true">⋮⋮</span>
                        <span class="cb-item-icon-wrap cb-item-icon-wrap--assignment">
                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </span>
                        <span class="cb-label">{{ Str::limit($a->title, 34) }}</span>
                    </a>
                @endforeach
            </div>
        @endif
        @if($uLessons->isNotEmpty() || $uQuizzes->isNotEmpty())
            <div class="cb-ungrouped">
                <div class="cb-section-label">Ungrouped</div>
                @foreach($uLessons as $l)
                    <a href="{{ route('instructor.lessons.edit', [$course, $l]) }}" class="cb-item {{ isset($lesson) && $lesson->id === $l->id ? 'active' : '' }}">
                        <span class="cb-drag" aria-hidden="true">⋮⋮</span>
                        <span class="cb-item-icon-wrap cb-item-icon-wrap--lesson">
                            @if($l->video_url)
                                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                            @else
                                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                            @endif
                        </span>
                        <span class="cb-label">{{ Str::limit($l->title, 34) }}</span>
                        @if($l->videoQuizzes->isNotEmpty())
                            <span class="cb-lesson-quiz-dot" title="Has in-video quiz" aria-label="Has in-video quiz"></span>
                        @endif
                    </a>
                @endforeach
                @foreach($uQuizzes as $q)
                    <a href="{{ route('instructor.quizzes.edit', [$course, $q]) }}" class="cb-item {{ isset($quiz) && $quiz->id === $q->id ? 'active' : '' }}">
                        <span class="cb-drag" aria-hidden="true">⋮⋮</span>
                        <span class="cb-item-icon-wrap cb-item-icon-wrap--quiz">
                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </span>
                        <span class="cb-label">{{ Str::limit($q->title, 34) }}</span>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
        </div>
        <div class="cb-sidebar-card__foot">
            <div class="cb-sidebar-foot-label">Add content</div>
            <div class="cb-actions">
                <a href="{{ route('instructor.lessons.create', $course) }}" class="cb-action-btn">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Add lesson
                </a>
                <a href="{{ route('instructor.quizzes.create', $course) }}" class="cb-action-btn">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Add quiz
                </a>
                <a href="{{ route('instructor.assignments.create', $course) }}" class="cb-action-btn">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Add assignment
                </a>
            </div>
        </div>
    </div>
</div>

{{-- Add Module modal --}}
<div class="modal fade" id="addModuleModal" tabindex="-1" aria-labelledby="addModuleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <form action="{{ route('instructor.modules.store', $course) }}" method="post">
                @csrf
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold" id="addModuleModalLabel">Add module</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pt-2">
                    <label class="form-label fw-semibold">Module title</label>
                    <input type="text" name="title" class="form-control rounded-3" placeholder="e.g. Computing fundamentals" required>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-outline-secondary rounded-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-dark rounded-3 px-4">Add module</button>
                </div>
            </form>
        </div>
    </div>
</div>
