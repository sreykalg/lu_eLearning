<section>
    <form id="send-verification" method="post" action="{{ route('verification.send') }}">@csrf</form>

    <form method="post" action="{{ route('profile.update') }}">
        @csrf
        @method('patch')
        <div class="mb-3">
            <label for="name" class="form-label">{{ __('Name') }}</label>
            <input id="name" name="name" type="text" class="form-control" value="{{ old('name', $user->name) }}" required>
            @error('name')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">{{ __('Email') }}</label>
            <input id="email" name="email" type="email" class="form-control" value="{{ old('email', $user->email) }}" required>
            @error('email')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <p class="small text-muted mt-2">
                    {{ __('Your email address is unverified.') }}
                    <button form="send-verification" type="submit" class="btn btn-link p-0 align-baseline">{{ __('Click here to re-send the verification email.') }}</button>
                </p>
                @if (session('status') === 'verification-link-sent')
                    <p class="small text-success">{{ __('A new verification link has been sent to your email address.') }}</p>
                @endif
            @endif
        </div>
        <button type="submit" class="btn btn-save">{{ __('Save') }}</button>
        @if (session('status') === 'profile-updated')
            <span class="ms-2 text-success small">{{ __('Saved.') }}</span>
        @endif
    </form>
</section>
