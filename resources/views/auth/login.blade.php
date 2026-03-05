<x-guest-layout title="Log in">
    <h2 class="auth-heading" style="color: var(--lu-deep-purple);">Welcome Back</h2>
    <p class="auth-subheading">Log in to continue your LU Academy journey.</p>

    @if (session('status'))
        <div class="alert alert-success mb-3">{{ session('status') }}</div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger mb-3">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="mb-3">
            <label for="email" class="form-label fw-medium text-dark">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}"
                   class="form-control form-control-glass" placeholder="your@email.com"
                   required autofocus autocomplete="username">
        </div>
        <div class="mb-3">
            <label for="password" class="form-label fw-medium text-dark">Password</label>
            <input id="password" type="password" name="password"
                   class="form-control form-control-glass" placeholder="••••••••"
                   required autocomplete="current-password">
        </div>
        <div class="mb-3 form-check">
            <input id="remember_me" type="checkbox" name="remember"
                   class="form-check-input" style="accent-color: var(--lu-deep-purple)">
            <label for="remember_me" class="form-check-label text-muted">Remember me</label>
        </div>
        @if (Route::has('password.request'))
            <div class="mb-4">
                <a href="{{ route('password.request') }}" class="text-decoration-none" style="color: var(--lu-purple);">Forgot your password?</a>
            </div>
        @else
            <div class="mb-4"></div>
        @endif
        <button type="submit" class="btn btn-lu-primary w-100 py-3">Log In</button>
    </form>

    @if (Route::has('register'))
        <p class="text-center auth-footer mt-4 mb-0">
            Don't have an account? <a href="{{ route('register') }}" class="text-decoration-none" style="color: var(--lu-purple);">Register</a>
        </p>
    @endif
</x-guest-layout>
