<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>LU Academy - {{ $title ?? 'Auth' }}</title>
    <link rel="icon" href="/images/life-university-logo.png" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.bunny.net/css?family=dm-sans:400,500,600,700&display=swap" rel="stylesheet">
    <style>
        :root { --lu-deep-purple: #2d1b4e; --lu-purple: #4c1d95; --lu-purple-light: rgba(76, 29, 149, 0.08); }
        body { font-family: 'DM Sans', sans-serif; min-height: 100vh; margin: 0; overflow-x: hidden; }
        .auth-bg {
            position: fixed;
            inset: 0;
            background: url('/images/campus-bg.png') center/cover no-repeat;
            filter: blur(2px) saturate(0.9);
            transform: scale(1.08);
        }
        .auth-overlay {
            position: fixed;
            inset: 0;
            background: linear-gradient(135deg, rgba(45, 27, 78, 0.5) 0%, rgba(76, 29, 149, 0.3) 100%);
            z-index: 0;
        }
        .auth-content {
            position: relative;
            z-index: 1;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(28px);
            -webkit-backdrop-filter: blur(28px);
            border-radius: 1.5rem;
            padding: 2.5rem 2.75rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.18), 0 0 0 1px rgba(255, 255, 255, 0.8) inset;
            max-width: 420px;
            width: 100%;
        }
        .auth-brand { border-bottom: 1px solid rgba(45, 27, 78, 0.08); padding-bottom: 1.5rem; margin-bottom: 1.5rem; }
        .glass-card .auth-logo { width: 72px; height: 72px; object-fit: contain; }
        .auth-brand-text { font-size: 1.35rem; font-weight: 700; letter-spacing: -0.02em; }
        .auth-heading { font-size: 1.5rem; font-weight: 700; letter-spacing: -0.02em; margin-bottom: 0.25rem; }
        .auth-subheading { font-size: 0.9rem; color: #64748b; margin-bottom: 1.5rem; }
        .form-control-glass {
            background: #f8fafc;
            border: 1px solid rgba(45, 27, 78, 0.1);
            border-radius: 0.625rem;
            padding: 0.65rem 1rem;
            font-size: 0.95rem;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .form-control-glass::placeholder { color: #94a3b8; }
        .form-control-glass:focus {
            background: #fff;
            border-color: var(--lu-purple);
            box-shadow: 0 0 0 3px rgba(76, 29, 149, 0.12);
            outline: none;
        }
        .form-label { font-size: 0.85rem; font-weight: 500; color: #334155; margin-bottom: 0.35rem; }
        .btn-lu-primary {
            background: linear-gradient(135deg, var(--lu-deep-purple) 0%, var(--lu-purple) 100%);
            color: #fff;
            border: none;
            padding: 0.7rem 1.5rem;
            border-radius: 0.625rem;
            font-weight: 600;
            font-size: 0.95rem;
            box-shadow: 0 4px 14px rgba(45, 27, 78, 0.35);
            transition: transform 0.15s, box-shadow 0.15s;
        }
        .btn-lu-primary:hover { color: #fff; transform: translateY(-1px); box-shadow: 0 6px 20px rgba(45, 27, 78, 0.4); }
        .auth-footer { font-size: 0.9rem; color: #64748b; }
        .auth-footer a { font-weight: 600; transition: color 0.15s; }
    </style>
    @stack('styles')
</head>
<body>
    <div class="auth-bg"></div>
    <div class="auth-overlay"></div>

    <div class="auth-content">
        <div class="glass-card">
            <a href="{{ url('/') }}" class="d-inline-flex align-items-center gap-2 text-decoration-none auth-brand">
                <img src="/images/life-university-logo.png" alt="LIFE UNIVERSITY" class="auth-logo">
                <span class="auth-brand-text" style="color: var(--lu-deep-purple);">LU Academy</span>
            </a>
            {{ $slot }}
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
