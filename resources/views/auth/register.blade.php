<x-guest-layout title="Register">
    <p class="auth-eyebrow">Sign up</p>
    <h2 class="auth-heading">Create your account</h2>
    <p class="auth-subheading">Join LU Academy and start learning with video lessons, quizzes, and more.</p>

    @if ($errors->any())
        <div class="alert auth-alert alert-danger mb-3" role="alert">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Full name</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}"
                   class="form-control form-control-glass" placeholder="Your name"
                   required autofocus autocomplete="name">
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}"
                   class="form-control form-control-glass" placeholder="you@example.com"
                   required autocomplete="username">
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input id="password" type="password" name="password"
                   class="form-control form-control-glass" placeholder="Create a strong password"
                   required autocomplete="new-password">
        </div>
        <div class="mb-4">
            <label for="password_confirmation" class="form-label">Confirm password</label>
            <input id="password_confirmation" type="password" name="password_confirmation"
                   class="form-control form-control-glass" placeholder="Repeat your password"
                   required autocomplete="new-password">
        </div>
        <button type="submit" class="btn btn-lu-primary w-100 py-3">Create account</button>
    </form>

    <div class="auth-divider">
        <p class="auth-footer text-center w-100 mb-0">
            Already have an account? <a href="{{ route('login') }}">Log in</a>
        </p>
    </div>
</x-guest-layout>
