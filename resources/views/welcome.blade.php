<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>LU Academy - LIFE University E-Learning</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.bunny.net/css?family=dm-sans:400,500,600,700&display=swap" rel="stylesheet">
    <style>
        :root { --lu-deep-purple: #2d1b4e; --lu-purple: #4c1d95; --lu-purple-light: rgba(76, 29, 149, 0.08); }
        body { font-family: 'DM Sans', sans-serif; min-height: 100vh; margin: 0; overflow-x: hidden; }
        .welcome-bg {
            position: fixed;
            inset: 0;
            background: url('/images/campus-bg.png') center/cover no-repeat;
            filter: blur(8px) saturate(0.9);
            transform: scale(1.08);
        }
        .welcome-overlay {
            position: fixed;
            inset: 0;
            background: linear-gradient(135deg, rgba(45, 27, 78, 0.5) 0%, rgba(76, 29, 149, 0.3) 100%);
            z-index: 0;
        }
        .welcome-content {
            position: relative;
            z-index: 1;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(28px);
            -webkit-backdrop-filter: blur(28px);
            border-radius: 1.5rem;
            padding: 2.75rem 3rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.18), 0 0 0 1px rgba(255, 255, 255, 0.8) inset;
            max-width: 500px;
            width: 100%;
            text-align: center;
        }
        .welcome-nav {
            position: absolute;
            top: 1.5rem;
            right: 1.5rem;
            z-index: 2;
        }
        .glass-nav .btn {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.6);
            border-radius: 0.75rem;
            font-weight: 600;
        }
        .glass-nav .btn-lu { background: var(--lu-deep-purple); color: #fff; border-color: var(--lu-deep-purple); }
        .glass-nav .btn-lu:hover { background: var(--lu-purple); color: #fff; border-color: var(--lu-purple); }
        .glass-card .logo { height: 96px; width: auto; }
        .glass-card h1 { color: var(--lu-deep-purple); font-weight: 700; font-size: 2rem; letter-spacing: -0.02em; margin-bottom: 0.5rem; }
        .glass-card .lead { color: #64748b; font-size: 0.95rem; line-height: 1.6; margin-bottom: 2rem; }
        .glass-card .btn-lu {
            background: linear-gradient(135deg, var(--lu-deep-purple) 0%, var(--lu-purple) 100%);
            color: #fff;
            border: none;
            padding: 0.7rem 1.75rem;
            border-radius: 0.625rem;
            font-weight: 600;
            box-shadow: 0 4px 14px rgba(45, 27, 78, 0.35);
            transition: transform 0.15s, box-shadow 0.15s;
        }
        .glass-card .btn-lu:hover { color: #fff; transform: translateY(-1px); box-shadow: 0 6px 20px rgba(45, 27, 78, 0.4); }
        .glass-card .btn-outline-glass {
            background: #fff;
            border: 1px solid rgba(45, 27, 78, 0.2);
            color: var(--lu-deep-purple);
            border-radius: 0.625rem;
            font-weight: 600;
            transition: border-color 0.15s, color 0.15s, background 0.15s;
        }
        .glass-card .btn-outline-glass:hover { background: var(--lu-purple-light); border-color: var(--lu-purple); color: var(--lu-purple); }
    </style>
</head>
<body>
    <div class="welcome-bg"></div>
    <div class="welcome-overlay"></div>

    <nav class="welcome-nav glass-nav d-flex gap-2">
        @auth
            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">Dashboard</a>
        @else
            <a href="{{ route('login') }}" class="btn btn-outline-secondary">Log in</a>
            <a href="{{ route('register') }}" class="btn btn-lu">Register</a>
        @endauth
    </nav>

    <div class="welcome-content">
        <div class="glass-card">
            <div class="d-flex align-items-center justify-content-center gap-3 mb-3">
                <img src="/images/life-university-logo.png" alt="LIFE UNIVERSITY" class="logo">
                <h1 class="mb-0">LU Academy</h1>
            </div>
            <p class="lead">LIFE University e-learning platform. Short video lessons, quizzes, assignments & community support.</p>
            <a href="{{ route('courses.index') }}" class="btn btn-lu btn-lg me-2 mb-2">Browse Courses</a>
            @guest
            <a href="{{ route('register') }}" class="btn btn-outline-glass btn-lg">Get Started</a>
            @endguest
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
