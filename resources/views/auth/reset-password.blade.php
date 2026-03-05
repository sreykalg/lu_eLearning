<x-guest-layout title="Reset Password">
    <h2 class="fw-bold text-dark mb-1">Reset Password</h2>
    <p class="text-muted mb-4">Enter your new password below.</p>

    @if ($errors->any())
        <div class="alert alert-danger mb-3">@foreach ($errors->all() as $error) <div>{{ $error }}</div> @endforeach</div>
    @endif

    <form method="POST" action="{{ route('password.store') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">
        <div class="mb-3">
            <label for="email" class="form-label fw-medium text-dark">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}"
                   class="form-control form-control-glass" required autofocus>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label fw-medium text-dark">Password</label>
            <input id="password" type="password" name="password" class="form-control form-control-glass" required>
        </div>
        <div class="mb-4">
            <label for="password_confirmation" class="form-label fw-medium text-dark">Confirm Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" class="form-control form-control-glass" required>
        </div>
        <button type="submit" class="btn btn-lu-primary w-100 py-3">Reset Password</button>
    </form>
</x-guest-layout>
