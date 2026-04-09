@extends('layouts.instructor-inner')

@push('styles')
<style>
    .cb-hero {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #334155 100%);
        border-radius: 1rem;
        padding: 1.35rem 1.5rem;
        color: #fff;
        margin-bottom: 1.25rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        flex-wrap: wrap;
        box-shadow: 0 12px 40px rgba(15, 23, 42, 0.2);
    }
    .cb-hero .hero-left { display: flex; align-items: center; gap: 1rem; }
    .cb-hero .hero-icon {
        width: 48px; height: 48px; border-radius: 0.85rem;
        background: rgba(255,255,255,0.14);
        display: flex; align-items: center; justify-content: center;
    }
    .cb-hero .hero-title { margin: 0; font-weight: 800; letter-spacing: -0.02em; font-size: 1.35rem; }
    .cb-hero .hero-subtitle { margin: 0.35rem 0 0; color: rgba(255,255,255,0.82); font-size: 0.9rem; max-width: 32rem; }
    .cb-hero .btn-create {
        border: 1px solid rgba(255,255,255,0.38);
        color: #fff;
        font-weight: 600;
        border-radius: 0.65rem;
        padding: 0.5rem 1rem;
    }
    .cb-hero .btn-create:hover { background: rgba(255,255,255,0.12); color: #fff; border-color: rgba(255,255,255,0.55); }
    .cb-card {
        border-radius: 0.95rem;
        overflow: hidden;
        border: 1px solid #e2e8f0;
        background: #fff;
        transition: transform 0.18s ease, box-shadow 0.18s ease;
        height: 100%;
    }
    .cb-card:hover { transform: translateY(-3px); box-shadow: 0 14px 36px rgba(15,23,42,0.1); }
    .cb-card-thumb {
        position: relative;
        aspect-ratio: 16 / 9;
        background: linear-gradient(145deg, #e2e8f0 0%, #f1f5f9 50%, #e2e8f0 100%);
        overflow: hidden;
    }
    .cb-card-thumb img { width: 100%; height: 100%; object-fit: cover; }
    .cb-card-thumb .placeholder-icon {
        position: absolute; inset: 0;
        display: flex; align-items: center; justify-content: center;
        color: #94a3b8;
    }
    .cb-card-body { padding: 1rem 1.05rem 1.15rem; }
    .cb-badges { display: flex; flex-wrap: wrap; gap: 0.4rem; margin-bottom: 0.65rem; }
    .cb-level {
        font-size: 0.65rem; font-weight: 700; letter-spacing: 0.05em;
        text-transform: uppercase;
        padding: 0.25rem 0.5rem; border-radius: 0.35rem;
    }
    .cb-level-beginner { background: #dbeafe; color: #1e40af; }
    .cb-level-intermediate { background: #fef3c7; color: #b45309; }
    .cb-level-advanced { background: #fce7f3; color: #9d174d; }
    .cb-status {
        font-size: 0.72rem; font-weight: 700;
        padding: 0.25rem 0.55rem; border-radius: 9999px;
    }
    .cb-status--approved { background: #dcfce7; color: #166534; }
    .cb-status--pending { background: #fef9c3; color: #854d0e; }
    .cb-status--revision { background: #fee2e2; color: #991b1b; }
    .cb-status--draft { background: #f1f5f9; color: #64748b; }
    .cb-title { font-size: 1.02rem; font-weight: 700; color: #0f172a; margin: 0 0 0.5rem; line-height: 1.35; }
    .cb-meta { font-size: 0.8rem; color: #64748b; margin-bottom: 0.85rem; }
    .cb-meta strong { color: #475569; font-weight: 600; }
    .cb-btn {
        display: inline-flex; align-items: center; justify-content: center;
        width: 100%;
        padding: 0.5rem 1rem;
        font-size: 0.875rem; font-weight: 600;
        border-radius: 0.65rem;
        border: 1px solid #0f172a;
        color: #0f172a;
        background: #fff;
        text-decoration: none;
        transition: background 0.15s, color 0.15s;
    }
    .cb-btn:hover { background: #0f172a; color: #fff; }
    .cb-btn:disabled, .cb-btn.disabled { opacity: 0.65; cursor: not-allowed; border-color: #cbd5e1; color: #94a3b8; background: #f8fafc; }
    .cb-empty {
        border: 1px dashed #cbd5e1;
        border-radius: 1rem;
        background: #fafbfc;
        padding: 3rem 1.5rem;
        text-align: center;
    }
</style>
@endpush

@section('content')
<div class="cb-hero">
    <div class="hero-left">
        <div class="hero-icon">
            <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z"/></svg>
        </div>
        <div>
            <h1 class="hero-title">Course Builder</h1>
            <p class="hero-subtitle">Manage and edit your courses — modules, lessons, quizzes, and assignments.</p>
        </div>
    </div>
    <a href="{{ route('instructor.courses.create') }}" class="btn btn-sm btn-create">Create course</a>
</div>

<div class="row g-4">
    @forelse($courses as $course)
        <div class="col-md-6 col-lg-4">
            <div class="cb-card h-100">
                @if(($course->approval_status ?? 'draft') === 'pending')
                <div class="text-decoration-none text-dark h-100 d-flex flex-column" style="cursor: default;">
                @else
                <a href="{{ route('instructor.courses.edit', $course) }}" class="text-decoration-none text-dark h-100 d-flex flex-column">
                @endif
                    <div class="cb-card-thumb">
                        @if($course->thumbnail)
                            <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="">
                        @else
                            <div class="placeholder-icon">
                                <svg width="52" height="52" fill="none" stroke="currentColor" stroke-width="1.25" viewBox="0 0 24 24"><path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                            </div>
                        @endif
                    </div>
                    <div class="cb-card-body flex-grow-1 d-flex flex-column">
                        @php
                            $statusLabel = match($course->approval_status ?? 'draft') {
                                'pending' => 'Pending Review',
                                'approved' => 'Active',
                                'needs_revision' => 'Needs Revision',
                                default => 'Draft',
                            };
                            $statusMod = match($course->approval_status ?? 'draft') {
                                'approved' => 'approved',
                                'pending' => 'pending',
                                'needs_revision' => 'revision',
                                default => 'draft',
                            };
                        @endphp
                        <div class="cb-badges">
                            <span class="cb-level cb-level-{{ $course->level ?? 'beginner' }}">{{ strtoupper($course->level ?? 'beginner') }}</span>
                            <span class="cb-status cb-status--{{ $statusMod }}">{{ $statusLabel }}</span>
                        </div>
                        <h2 class="cb-title">{{ Str::limit($course->title, 52) }}</h2>
                        <p class="cb-meta mb-0">
                            <strong>{{ $course->lessons_count }}</strong> {{ Str::plural('lesson', $course->lessons_count) }}
                            ·
                            <strong>{{ $course->quizzes_count }}</strong> {{ Str::plural('quiz', $course->quizzes_count) }}
                            ·
                            <strong>{{ $course->assignments_count }}</strong> {{ Str::plural('assignment', $course->assignments_count) }}
                        </p>
                        <div class="mt-auto pt-2">
                            @if(($course->approval_status ?? 'draft') === 'pending')
                                <span class="cb-btn disabled w-100 d-block text-center">Under review — editing disabled</span>
                            @else
                                <span class="cb-btn w-100">Edit course</span>
                            @endif
                        </div>
                    </div>
                @if(($course->approval_status ?? 'draft') === 'pending')
                </div>
                @else
                </a>
                @endif
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="cb-empty">
                <svg class="mb-3 text-muted opacity-50" width="56" height="56" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13"/></svg>
                <p class="text-muted mb-3 mb-0">No courses yet. Create your first course to get started.</p>
                <a href="{{ route('instructor.courses.create') }}" class="btn btn-dark rounded-3 px-4 mt-3">Create course</a>
            </div>
        </div>
    @endforelse
</div>
@endsection
