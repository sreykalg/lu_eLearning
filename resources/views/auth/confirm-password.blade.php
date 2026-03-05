<x-guest-layout title="Confirm Password">
    <h2 class="fw-bold text-dark mb-1">Confirm Password</h2>
    <p class="text-muted mb-4">This is a secure area. Please confirm your password to continue.</p>

    @if ($errors->any())
        <div class="alert alert-danger mb-3">@foreach ($errors->all() as $error) <div>{{ $error }}</div> @endforeach</div>
    @endif

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf
        <div class="mb-4">
            <label for="password" class="form-label fw-medium text-dark">Password</label>
            <input id="password" type="password" name="password" class="form-control form-control-glass" required>
        </div>
        <button type="submit" class="btn btn-lu-primary w-100 py-3">Confirm</button>
    </form>
</x-guest-layout>
