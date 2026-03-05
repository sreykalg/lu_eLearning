<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'LU Learn') }} - {{ $title ?? 'Auth' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.bunny.net/css?family=dm-sans:400,500,600,700&display=swap" rel="stylesheet">
    <style>
        :root {
            --lu-deep-purple: #2d1b4e;
            --lu-purple: #4c1d95;
            --lu-light-bg: #f8f7fc;
        }
        body { font-family: 'DM Sans', sans-serif; min-height: 100vh; }
        .auth-split-left {
            background: var(--lu-deep-purple);
            min-height: 100vh;
            position: relative;
            overflow: hidden;
            border-radius: 0 2rem 2rem 0;
        }
        .auth-split-left::before {
            content: '';
            position: absolute;
            inset: 0;
            background: url('/images/campus-bg.png') center/cover;
            opacity: 0.15;
            filter: blur(8px);
        }
        .auth-split-left .dots-pattern {
            position: absolute;
            width: 80px;
            height: 80px;
            background-image: radial-gradient(rgba(255,255,255,0.2) 2px, transparent 2px);
            background-size: 12px 12px;
        }
        .auth-split-left .dots-pattern.top-left { top: 2rem; left: 2rem; }
        .auth-split-left .dots-pattern.bottom-left { bottom: 2rem; left: 2rem; }
        .auth-split-right {
            background: var(--lu-light-bg);
            min-height: 100vh;
            position: relative;
        }
        .auth-split-right .dots-pattern {
            position: absolute;
            width: 60px;
            height: 60px;
            background-image: radial-gradient(rgba(45,27,78,0.08) 2px, transparent 2px);
            background-size: 10px 10px;
        }
        .auth-split-right .dots-pattern.top-right { top: 2rem; right: 2rem; }
        .auth-split-right .dots-pattern.bottom-right { bottom: 2rem; right: 2rem; }
        .auth-logo {
            width: 100px;
            height: 100px;
            object-fit: contain;
        }
        .auth-brand { font-size: 1.5rem; font-weight: 700; color: var(--lu-deep-purple); }
        .form-control-glass {
            background: rgba(255,255,255,0.7);
            border: 1px solid rgba(45,27,78,0.1);
            border-radius: 0.75rem;
            padding: 0.75rem 1rem;
            box-shadow: inset 0 1px 2px rgba(0,0,0,0.04);
        }
        .form-control-glass:focus {
            background: #fff;
            border-color: var(--lu-purple);
            box-shadow: 0 0 0 3px rgba(76,29,149,0.15);
        }
        .btn-lu-primary {
            background: var(--lu-deep-purple);
            color: #fff;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 0.75rem;
            font-weight: 600;
            box-shadow: 0 2px 8px rgba(45,27,78,0.3);
        }
        .btn-lu-primary:hover { background: var(--lu-purple); color: #fff; }
    </style>
    @stack('styles')
</head>
<body class="bg-white">
    <div class="container-fluid p-0">
        <div class="row g-0 min-vh-100">
            {{-- Left: Deep purple with blurred campus background --}}
            <div class="col-lg-6 auth-split-left d-none d-lg-flex align-items-center justify-content-center position-relative">
                <div class="dots-pattern top-left"></div>
                <div class="dots-pattern bottom-left"></div>
                <div class="position-relative text-center text-white px-5">
                    <img src="/images/life-university-logo.png" alt="LIFE UNIVERSITY" class="auth-logo mb-4" style="filter: brightness(0) invert(1); opacity: 0.9;">
                    <h2 class="fw-bold mb-2">Welcome to LU Learn</h2>
                    <p class="opacity-90">LIFE University e-learning platform. Study at your own pace.</p>
                </div>
            </div>
            {{-- Right: Form area --}}
            <div class="col-12 col-lg-6 auth-split-right d-flex align-items-center justify-content-center p-4 p-lg-5 position-relative">
                <div class="dots-pattern top-right"></div>
                <div class="dots-pattern bottom-right"></div>
                <div class="w-100" style="max-width: 420px;">
                    <a href="{{ url('/') }}" class="d-inline-block mb-4">
                        <img src="/images/life-university-logo.png" alt="LIFE UNIVERSITY" class="auth-logo">
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
