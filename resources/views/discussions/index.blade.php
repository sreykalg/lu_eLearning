<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="mb-0 fw-bold" style="color: var(--lu-deep-purple);">Community</h2>
            @auth
                <button type="button" class="btn btn-lu-primary" data-bs-toggle="modal" data-bs-target="#newQuestionModal">Ask Question</button>
            @endauth
        </div>
    </x-slot>

    <div class="container">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-lg-8">
                @forelse ($discussions as $d)
                    <div class="card mb-3 border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <h5 class="card-title mb-1">
                                    <a href="{{ route('discussions.show', $d) }}" class="text-decoration-none text-dark">{{ $d->title }}</a>
                                </h5>
                                @if($d->is_pinned) <span class="badge bg-secondary">Pinned</span> @endif
                                @if($d->is_resolved) <span class="badge bg-success">Resolved</span> @endif
                            </div>
                            <p class="text-muted small mb-2">{{ Str::limit($d->body, 120) }}</p>
                            <div class="d-flex gap-2 text-muted small">
                                <span>{{ $d->user->name }}</span>
                                @if($d->course) <span>·</span><span>{{ $d->course->title }}</span> @endif
                                <span>·</span><span>{{ $d->replies->count() }} replies</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5 text-muted">No questions yet. Be the first to ask!</div>
                @endforelse
                {{ $discussions->links() }}
            </div>
        </div>
    </div>

    @auth
    <div class="modal fade" id="newQuestionModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ route('discussions.store') }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Ask a Question</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Title</label>
                            <input type="text" name="title" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Question</label>
                            <textarea name="body" class="form-control" rows="4" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Course (optional)</label>
                            <select name="course_id" class="form-select">
                                <option value="">General</option>
                                @foreach($courses as $c)
                                    <option value="{{ $c->id }}">{{ $c->title }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-lu-primary">Post</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endauth
</x-app-layout>
