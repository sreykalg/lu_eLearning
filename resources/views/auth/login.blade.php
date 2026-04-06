<x-guest-layout title="Log in">
    <p class="auth-eyebrow">Sign in</p>
    <h2 class="auth-heading">Welcome back</h2>
    <p class="auth-subheading">Log in to continue your LU Academy journey.</p>

    @if (session('status'))
        <div class="alert auth-alert alert-success mb-3" role="alert">{{ session('status') }}</div>
    @endif
    @if ($errors->any())
        <div class="alert auth-alert alert-danger mb-3" role="alert">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}"
                   class="form-control form-control-glass" placeholder="you@example.com"
                   required autofocus autocomplete="username">
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input id="password" type="password" name="password"
                   class="form-control form-control-glass" placeholder="Enter your password"
                   required autocomplete="current-password">
        </div>
        <div class="auth-options-row">
            <div class="form-check">
                <input id="remember_me" type="checkbox" name="remember"
                       class="form-check-input" style="accent-color: #0f172a;">
                <label for="remember_me" class="form-check-label">Remember me</label>
            </div>
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="auth-forgot">Forgot password?</a>
            @endif
        </div>
        <button type="submit" class="btn btn-lu-primary w-100 py-3">Log in</button>
    </form>

    @if (Route::has('register'))
        <div class="auth-divider">
            <p class="auth-footer text-center w-100 mb-0">
                Don’t have an account? <a href="{{ route('register') }}">Create one</a>
            </p>
        </div>
    @endif
</x-guest-layout>
