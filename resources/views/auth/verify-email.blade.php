<x-guest-layout title="Verify Email">
    <h2 class="fw-bold text-dark mb-1">Verify Email</h2>
    <p class="text-muted mb-4">Thanks for signing up! Please verify your email by clicking the link we sent you.</p>

    @if (session('status') == 'verification-link-sent')
        <div class="alert alert-success mb-4">A new verification link has been sent to your email.</div>
    @endif

    <div class="d-flex gap-2 flex-wrap">
        <form method="POST" action="{{ route('verification.send') }}">@csrf
            <button type="submit" class="btn btn-lu-primary">Resend Verification Email</button>
        </form>
        <form method="POST" action="{{ route('logout') }}">@csrf
            <button type="submit" class="btn btn-outline-secondary">Log Out</button>
        </form>
    </div>
</x-guest-layout>
