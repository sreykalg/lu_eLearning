<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>LU Academy - {{ $title ?? 'Auth' }}</title>
    <link rel="icon" href="/images/life-university-logo.png" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.bunny.net/css?family=dm-sans:400,500,600,700,800&display=swap" rel="stylesheet">
    <style>
        :root {
            --lu-ink: #0f172a;
            --lu-slate: #1e293b;
            --lu-muted: #64748b;
            --lu-purple-light: rgba(15, 23, 42, 0.08);
        }
        * { box-sizing: border-box; }
        body {
            font-family: 'DM Sans', system-ui, sans-serif;
            min-height: 100vh;
            margin: 0;
            overflow-x: hidden;
            color: var(--lu-ink);
        }
        .auth-bg {
            position: fixed;
            inset: 0;
            background: url('/images/life-stadium-bg.png') center 28% / cover no-repeat;
            filter: blur(0.5px) saturate(1.08);
            transform: scale(1.04);
        }
        .auth-overlay {
            position: fixed;
            inset: 0;
            z-index: 0;
            background:
                radial-gradient(ellipse 90% 60% at 50% 100%, rgba(15, 23, 42, 0.72) 0%, transparent 55%),
                radial-gradient(ellipse 70% 45% at 80% 20%, rgba(30, 58, 138, 0.18) 0%, transparent 50%),
                linear-gradient(165deg, rgba(15, 23, 42, 0.42) 0%, rgba(15, 23, 42, 0.22) 55%, rgba(15, 23, 42, 0.58) 100%);
        }
        .auth-content {
            position: relative;
            z-index: 1;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 1.5rem 1.25rem 2rem;
        }
        .auth-panel {
            width: 100%;
            max-width: 440px;
        }
        .auth-back {
            margin-bottom: 1.1rem;
        }
        .auth-back-link {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            font-size: 0.8125rem;
            font-weight: 600;
            color: var(--lu-muted);
            text-decoration: none;
            transition: color 0.15s;
        }
        .auth-back-link:hover {
            color: var(--lu-ink);
        }
        .auth-back-link svg {
            flex-shrink: 0;
            opacity: 0.85;
        }
        .glass-card {
            position: relative;
            width: 100%;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(22px);
            -webkit-backdrop-filter: blur(22px);
            border-radius: 1.75rem;
            padding: 0;
            border: 1px solid rgba(255, 255, 255, 0.65);
            box-shadow:
                0 4px 6px -1px rgba(0, 0, 0, 0.06),
                0 24px 48px -12px rgba(15, 23, 42, 0.22),
                inset 0 1px 0 rgba(255, 255, 255, 0.95);
            overflow: hidden;
        }
        .glass-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 36%;
            height: 3px;
            border-radius: 0 0 0.25rem 0.25rem;
            background: linear-gradient(90deg, transparent, var(--lu-ink), #334155, transparent);
            opacity: 0.85;
        }
        .glass-card-inner { padding: 2rem 1.85rem 2.15rem; }
        @media (min-width: 480px) {
            .glass-card-inner { padding: 2.25rem 2.25rem 2.5rem; }
        }
        .auth-brand {
            display: flex;
            align-items: center;
            gap: 0.85rem;
            text-decoration: none;
            padding-bottom: 1.35rem;
            margin-bottom: 1.35rem;
            border-bottom: 1px solid rgba(15, 23, 42, 0.08);
        }
        .auth-brand .auth-logo {
            width: 56px;
            height: 56px;
            object-fit: contain;
            flex-shrink: 0;
            filter: drop-shadow(0 2px 6px rgba(15, 23, 42, 0.12));
        }
        .auth-brand-text-block {
            display: flex;
            flex-direction: column;
            line-height: 1.15;
            min-width: 0;
        }
        .auth-brand-title {
            font-size: 1.35rem;
            font-weight: 800;
            letter-spacing: -0.03em;
            color: var(--lu-ink);
        }
        .auth-brand-sub {
            font-size: 0.65rem;
            font-weight: 600;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: var(--lu-muted);
            margin-top: 0.2rem;
        }
        .auth-eyebrow {
            font-size: 0.68rem;
            font-weight: 700;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: var(--lu-muted);
            margin: 0 0 0.4rem;
        }
        .auth-heading {
            font-size: 1.55rem;
            font-weight: 800;
            letter-spacing: -0.035em;
            line-height: 1.2;
            color: var(--lu-ink);
            margin: 0 0 0.45rem;
        }
        .auth-subheading {
            font-size: 0.92rem;
            line-height: 1.55;
            color: #475569;
            margin: 0 0 1.5rem;
        }
        .auth-alert {
            border-radius: 0.65rem;
            border: none;
            font-size: 0.875rem;
        }
        .auth-alert.alert-success { background: #ecfdf5; color: #065f46; }
        .auth-alert.alert-danger { background: #fef2f2; color: #991b1b; }
        .form-control-glass {
            background: rgba(248, 250, 252, 0.95);
            border: 1px solid rgba(15, 23, 42, 0.1);
            border-radius: 0.7rem;
            padding: 0.72rem 1rem;
            font-size: 0.95rem;
            transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
        }
        .form-control-glass::placeholder { color: #94a3b8; }
        .form-control-glass:focus {
            background: #fff;
            border-color: var(--lu-ink);
            box-shadow: 0 0 0 3px rgba(15, 23, 42, 0.1);
            outline: none;
        }
        .form-label {
            font-size: 0.8125rem;
            font-weight: 600;
            color: #334155;
            margin-bottom: 0.4rem;
        }
        .auth-options-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-bottom: 1.35rem;
        }
        .auth-options-row .form-check { margin: 0; }
        .auth-options-row .form-check-label { font-size: 0.875rem; color: #64748b; }
        .auth-forgot {
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--lu-slate);
            text-decoration: none;
        }
        .auth-forgot:hover { color: var(--lu-ink); text-decoration: underline; }
        .btn-lu-primary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.4rem;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            color: #fff;
            border: none;
            padding: 0.85rem 1.5rem;
            border-radius: 0.75rem;
            font-weight: 700;
            font-size: 0.95rem;
            box-shadow: 0 6px 20px rgba(15, 23, 42, 0.32);
            transition: transform 0.15s, box-shadow 0.2s;
        }
        .btn-lu-primary:hover {
            background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 10px 28px rgba(15, 23, 42, 0.38);
        }
        .auth-divider {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin: 1.5rem 0 0;
            padding-top: 1.25rem;
            border-top: 1px solid rgba(15, 23, 42, 0.07);
        }
        .auth-footer {
            font-size: 0.9rem;
            color: #64748b;
            margin: 0;
        }
        .auth-footer a {
            font-weight: 700;
            color: var(--lu-ink);
            text-decoration: none;
        }
        .auth-footer a:hover { text-decoration: underline; color: var(--lu-slate); }
    </style>
    @stack('styles')
</head>
<body>
    @include('layouts.partials.screen-size-restriction')
    <div class="auth-bg" aria-hidden="true"></div>
    <div class="auth-overlay" aria-hidden="true"></div>

    <div class="auth-content">
        <div class="auth-panel">
            <div class="glass-card">
                <div class="glass-card-inner">
                    <div class="auth-back">
                        <a href="{{ url('/') }}" class="auth-back-link">
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                            Back to home
                        </a>
                    </div>
                    <a href="{{ url('/') }}" class="auth-brand">
                        <img src="/images/life-university-logo.png" alt="" class="auth-logo" width="56" height="56">
                        <span class="auth-brand-text-block">
                            <span class="auth-brand-title">LU Academy</span>
                            <span class="auth-brand-sub">Life University</span>
                        </span>
                    </a>
                    {{ $slot }}
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
