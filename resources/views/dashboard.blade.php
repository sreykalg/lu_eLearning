<x-app-layout>
    <x-slot name="header">
        <h2 class="mb-0 fw-bold" style="color: var(--lu-deep-purple);">{{ __('Dashboard') }}</h2>
    </x-slot>

    <div class="container">
        <div class="mb-5">
            <h1 class="h3 fw-bold" style="color: var(--lu-deep-purple);">Welcome back, {{ Auth::user()->name }}!</h1>
            <p class="text-muted">Continue learning or explore new courses.</p>
        </div>

        <div class="row g-4">
            <div class="col-md-6">
                <a href="{{ route('courses.index') }}" class="card text-decoration-none border-0 shadow-sm h-100 hover-shadow">
                    <div class="card-body d-flex align-items-center gap-3">
                        <span class="rounded-3 p-3 d-flex align-items-center justify-content-center" style="background: rgba(45,27,78,0.1); color: var(--lu-deep-purple);">
                            <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        </span>
                        <div>
                            <h5 class="card-title mb-1" style="color: var(--lu-deep-purple);">Browse Courses</h5>
                            <p class="card-text text-muted small mb-0">Explore available courses and start learning</p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-6">
                <a href="{{ route('profile.edit') }}" class="card text-decoration-none border-0 shadow-sm h-100">
                    <div class="card-body d-flex align-items-center gap-3">
                        <span class="rounded-3 p-3 d-flex align-items-center justify-content-center" style="background: rgba(45,27,78,0.1); color: var(--lu-deep-purple);">
                            <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </span>
                        <div>
                            <h5 class="card-title mb-1" style="color: var(--lu-deep-purple);">Profile</h5>
                            <p class="card-text text-muted small mb-0">Update your account settings</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
