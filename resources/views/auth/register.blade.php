<x-guest-layout title="Register">
    <h2 class="fw-bold text-dark mb-1">Create Account</h2>
    <p class="text-muted mb-4">Join LU Learn to start your learning journey.</p>

    @if ($errors->any())
        <div class="alert alert-danger mb-3">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label fw-medium text-dark">Name</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}"
                   class="form-control form-control-glass" placeholder="Your name"
                   required autofocus autocomplete="name">
        </div>
        <div class="mb-3">
            <label for="email" class="form-label fw-medium text-dark">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}"
                   class="form-control form-control-glass" placeholder="your@email.com"
                   required autocomplete="username">
        </div>
        <div class="mb-3">
            <label for="password" class="form-label fw-medium text-dark">Password</label>
            <input id="password" type="password" name="password"
                   class="form-control form-control-glass" placeholder="••••••••"
                   required autocomplete="new-password">
        </div>
        <div class="mb-4">
            <label for="password_confirmation" class="form-label fw-medium text-dark">Confirm Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation"
                   class="form-control form-control-glass" placeholder="••••••••"
                   required autocomplete="new-password">
        </div>
        <button type="submit" class="btn btn-lu-primary w-100 py-3">Register</button>
    </form>

    <p class="text-center text-muted mt-4 mb-0">
        Already have an account? <a href="{{ route('login') }}" class="fw-medium text-decoration-none" style="color: var(--lu-purple);">Log In</a>
    </p>
</x-guest-layout>
