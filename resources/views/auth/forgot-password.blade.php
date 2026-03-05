<x-guest-layout title="Forgot Password">
    <h2 class="fw-bold text-dark mb-1">Forgot Password?</h2>
    <p class="text-muted mb-4">Enter your email and we'll send you a reset link.</p>

    @if (session('status'))
        <div class="alert alert-success mb-3">{{ session('status') }}</div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger mb-3">@foreach ($errors->all() as $error) <div>{{ $error }}</div> @endforeach</div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf
        <div class="mb-4">
            <label for="email" class="form-label fw-medium text-dark">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}"
                   class="form-control form-control-glass" required autofocus>
        </div>
        <button type="submit" class="btn btn-lu-primary w-100 py-3">Email Password Reset Link</button>
    </form>
</x-guest-layout>
