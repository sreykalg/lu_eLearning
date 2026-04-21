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
                   required autocomplete="new-password"
                   aria-describedby="password-requirements">
            <ul class="pwd-checklist list-unstyled mb-0 mt-2 small" id="password-requirements" role="list" aria-label="Password requirements">
                <li id="pwd-rule-length" class="pwd-check-item">At least 8 characters</li>
                <li id="pwd-rule-letters" class="pwd-check-item">At least one letter</li>
                <li id="pwd-rule-numbers" class="pwd-check-item">At least one number</li>
                <li id="pwd-rule-symbols" class="pwd-check-item">At least one symbol (e.g. ! @ #)</li>
            </ul>
        </div>
        <div class="mb-4">
            <label for="password_confirmation" class="form-label">Confirm password</label>
            <input id="password_confirmation" type="password" name="password_confirmation"
                   class="form-control form-control-glass" placeholder="Repeat your password"
                   required autocomplete="new-password"
                   aria-describedby="password-match-hint">
            <p class="pwd-match-hint small mb-0 mt-2" id="password-match-hint" role="status" aria-live="polite"></p>
        </div>
        <button type="submit" class="btn btn-lu-primary w-100 py-3">Create account</button>
    </form>

    <div class="auth-divider">
        <p class="auth-footer text-center w-100 mb-0">
            Already have an account? <a href="{{ route('login') }}">Log in</a>
        </p>
    </div>

    @push('styles')
        <style>
            .pwd-checklist { color: #64748b; line-height: 1.45; }
            .pwd-check-item {
                display: flex;
                align-items: flex-start;
                gap: 0.4rem;
                margin-bottom: 0.2rem;
            }
            .pwd-check-item::before {
                content: '';
                width: 6px;
                height: 6px;
                margin-top: 0.38rem;
                border-radius: 50%;
                background: #cbd5e1;
                flex-shrink: 0;
            }
            .pwd-check-item.is-met { color: #047857; }
            .pwd-check-item.is-met::before { background: #10b981; }
            .pwd-match-hint { min-height: 1.25rem; color: #64748b; }
            .pwd-match-hint.is-match { color: #047857; }
            .pwd-match-hint.is-mismatch { color: #991b1b; }
        </style>
    @endpush

    @push('scripts')
        <script>
            (function () {
                var pwd = document.getElementById('password');
                var conf = document.getElementById('password_confirmation');
                var matchHint = document.getElementById('password-match-hint');
                if (!pwd) return;

                var symbolRe = /\p{Z}|\p{S}|\p{P}/u;

                function refreshPasswordRules() {
                    var v = pwd.value || '';
                    var checks = {
                        'pwd-rule-length': v.length >= 8,
                        'pwd-rule-letters': /\p{L}/u.test(v),
                        'pwd-rule-numbers': /\p{N}/u.test(v),
                        'pwd-rule-symbols': symbolRe.test(v),
                    };
                    Object.keys(checks).forEach(function (id) {
                        var el = document.getElementById(id);
                        if (!el) return;
                        el.classList.toggle('is-met', checks[id]);
                    });
                }

                function refreshMatchHint() {
                    if (!matchHint || !conf) return;
                    var p = pwd.value;
                    var c = conf.value;
                    matchHint.textContent = '';
                    matchHint.classList.remove('is-match', 'is-mismatch');
                    if (!c.length) return;
                    if (p === c) {
                        matchHint.textContent = 'Passwords match.';
                        matchHint.classList.add('is-match');
                    } else {
                        matchHint.textContent = 'Passwords do not match yet.';
                        matchHint.classList.add('is-mismatch');
                    }
                }

                pwd.addEventListener('input', function () {
                    refreshPasswordRules();
                    refreshMatchHint();
                });
                if (conf) {
                    conf.addEventListener('input', refreshMatchHint);
                }
                refreshPasswordRules();
            })();
        </script>
    @endpush
</x-guest-layout>
