<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>LU Academy - LIFE University E-Learning</title>
    <link rel="icon" href="/images/life-university-logo.png" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.bunny.net/css?family=dm-sans:400,500,600,700,800&display=swap" rel="stylesheet">
    <style>
        :root {
            --lu-ink: #0f172a;
            --lu-slate: #1e293b;
            --lu-muted: #64748b;
        }
        * { box-sizing: border-box; }
        body {
            font-family: 'DM Sans', system-ui, sans-serif;
            min-height: 100vh;
            margin: 0;
            overflow-x: hidden;
            color: var(--lu-ink);
        }
        .welcome-bg {
            position: fixed;
            inset: 0;
            background: url('/images/life-stadium-bg.png') center 28% / cover no-repeat;
            filter: blur(0.5px) saturate(1.08);
            transform: scale(1.04);
        }
        .welcome-overlay {
            position: fixed;
            inset: 0;
            z-index: 0;
            background:
                radial-gradient(ellipse 90% 60% at 50% 100%, rgba(15, 23, 42, 0.72) 0%, transparent 55%),
                radial-gradient(ellipse 70% 45% at 80% 20%, rgba(30, 58, 138, 0.18) 0%, transparent 50%),
                linear-gradient(165deg, rgba(15, 23, 42, 0.42) 0%, rgba(15, 23, 42, 0.22) 55%, rgba(15, 23, 42, 0.58) 100%);
        }
        .welcome-shell {
            position: relative;
            z-index: 1;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .welcome-header-wrap {
            width: 100%;
            max-width: 1120px;
            margin: 0 auto;
            padding: 1rem 1.25rem 0;
        }
        @media (min-width: 768px) {
            .welcome-header-wrap { padding: 1.25rem 2rem 0; }
        }
        .welcome-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            padding: 0.65rem 1rem 0.65rem 0.85rem;
            border-radius: 1rem;
            background: rgba(255, 255, 255, 0.58);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.72);
            box-shadow:
                0 4px 24px rgba(15, 23, 42, 0.08),
                0 1px 0 rgba(255, 255, 255, 0.9) inset;
        }
        @media (min-width: 576px) {
            .welcome-header { padding: 0.7rem 1.15rem 0.7rem 1rem; }
        }
        .brand-link {
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
            color: var(--lu-ink);
        }
        .brand-link img {
            width: 44px;
            height: 44px;
            object-fit: contain;
            filter: drop-shadow(0 1px 2px rgba(15, 23, 42, 0.12));
        }
        .brand-text {
            display: flex;
            flex-direction: column;
            line-height: 1.15;
        }
        .brand-text strong {
            font-size: 1.05rem;
            font-weight: 800;
            letter-spacing: -0.03em;
        }
        .brand-text span {
            font-size: 0.7rem;
            font-weight: 600;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: var(--lu-muted);
        }
        .header-actions {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .btn-nav {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
            font-weight: 600;
            border-radius: 0.65rem;
            text-decoration: none;
            transition: background 0.2s, box-shadow 0.2s, transform 0.15s, border-color 0.2s;
        }
        .btn-nav--ghost {
            background: rgba(255, 255, 255, 0.75);
            border: 1px solid rgba(15, 23, 42, 0.12);
            color: var(--lu-ink);
        }
        .btn-nav--ghost:hover {
            background: #fff;
            color: var(--lu-ink);
            border-color: rgba(15, 23, 42, 0.22);
        }
        .btn-nav--solid {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            color: #fff;
            border: 1px solid rgba(255, 255, 255, 0.12);
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.25);
        }
        .btn-nav--solid:hover {
            color: #fff;
            transform: translateY(-1px);
            box-shadow: 0 4px 18px rgba(0, 0, 0, 0.35);
        }
        .welcome-main {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem 1.25rem 2.5rem;
        }
        .hero-card {
            width: 100%;
            max-width: 540px;
            padding: 2rem 1.75rem 2.25rem;
            text-align: center;
            border-radius: 1.75rem;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.55);
            box-shadow:
                0 4px 6px -1px rgba(0, 0, 0, 0.06),
                0 24px 48px -12px rgba(15, 23, 42, 0.22),
                inset 0 1px 0 rgba(255, 255, 255, 0.95);
            position: relative;
            overflow: hidden;
        }
        @media (min-width: 576px) {
            .hero-card { padding: 2.5rem 2.5rem 2.75rem; }
        }
        .hero-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 40%;
            height: 3px;
            border-radius: 0 0 0.25rem 0.25rem;
            background: linear-gradient(90deg, transparent, #0f172a, #334155, transparent);
            opacity: 0.9;
        }
        .hero-eyebrow {
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: var(--lu-muted);
            margin: 0 0 0.75rem;
        }
        .hero-title {
            font-size: clamp(1.65rem, 4vw, 2.25rem);
            font-weight: 800;
            letter-spacing: -0.035em;
            line-height: 1.15;
            color: var(--lu-ink);
            margin: 0 0 0.75rem;
        }
        .hero-title .accent {
            background: linear-gradient(135deg, #0f172a 0%, #334155 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .hero-lead {
            font-size: 0.98rem;
            line-height: 1.65;
            color: #475569;
            margin: 0 auto 1.25rem;
            max-width: 38ch;
        }
        .hero-chips {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 0.45rem;
            margin-bottom: 1.75rem;
        }
        .hero-chip {
            font-size: 0.72rem;
            font-weight: 600;
            color: #475569;
            background: rgba(255, 255, 255, 0.9);
            border: 1px solid rgba(15, 23, 42, 0.08);
            padding: 0.35rem 0.65rem;
            border-radius: 9999px;
        }
        .hero-cta {
            display: flex;
            flex-direction: column;
            gap: 0.65rem;
            align-items: stretch;
        }
        @media (min-width: 420px) {
            .hero-cta {
                flex-direction: row;
                justify-content: center;
                align-items: center;
            }
        }
        .btn-hero-primary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.4rem;
            padding: 0.85rem 1.5rem;
            font-size: 0.95rem;
            font-weight: 700;
            border-radius: 0.75rem;
            border: none;
            color: #fff;
            text-decoration: none;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            box-shadow: 0 6px 20px rgba(15, 23, 42, 0.35);
            transition: transform 0.15s, box-shadow 0.2s;
        }
        .btn-hero-primary:hover {
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 10px 28px rgba(15, 23, 42, 0.4);
        }
        .btn-hero-secondary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.85rem 1.5rem;
            font-size: 0.95rem;
            font-weight: 600;
            border-radius: 0.75rem;
            color: var(--lu-ink);
            text-decoration: none;
            background: rgba(255, 255, 255, 0.65);
            border: 1px solid rgba(15, 23, 42, 0.12);
            transition: background 0.2s, border-color 0.2s;
        }
        .btn-hero-secondary:hover {
            background: #fff;
            border-color: rgba(15, 23, 42, 0.22);
            color: var(--lu-ink);
        }
        .hero-footnote {
            margin: 1.35rem 0 0;
            font-size: 0.78rem;
            color: #94a3b8;
        }
    </style>
</head>
<body>
    <div class="welcome-bg" aria-hidden="true"></div>
    <div class="welcome-overlay" aria-hidden="true"></div>

    <div class="welcome-shell">
        <header class="welcome-header-wrap">
            <div class="welcome-header">
                <a href="{{ url('/') }}" class="brand-link">
                    <img src="/images/life-university-logo.png" alt="">
                    <span class="brand-text">
                        <strong>LU Academy</strong>
                        <span>LIFE University</span>
                    </span>
                </a>
                <nav class="header-actions" aria-label="Account">
                    @auth
                        <a href="{{ route('dashboard') }}" class="btn-nav btn-nav--solid">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="btn-nav btn-nav--ghost">Log in</a>
                        <a href="{{ route('register') }}" class="btn-nav btn-nav--solid">Register</a>
                    @endauth
                </nav>
            </div>
        </header>

        <main class="welcome-main">
            <div class="hero-card">
                <p class="hero-eyebrow">E-learning</p>
                <h1 class="hero-title">Learn anywhere.<br><span class="accent">Grow together.</span></h1>
                <p class="hero-lead">Short video lessons, quizzes, assignments, and community support—built for LIFE University students and instructors.</p>
                <div class="hero-chips">
                    <span class="hero-chip">Video lessons</span>
                    <span class="hero-chip">Quizzes & exams</span>
                    <span class="hero-chip">Assignments</span>
                    <span class="hero-chip">Discussions</span>
                </div>
                <div class="hero-cta">
                    <a href="{{ route('courses.index') }}" class="btn-hero-primary">
                        Browse courses
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                    </a>
                    @guest
                        <a href="{{ route('register') }}" class="btn-hero-secondary">Create an account</a>
                    @endguest
                </div>
                @guest
                    <p class="hero-footnote mb-0">Already have access? Use Log in above.</p>
                @endguest
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
