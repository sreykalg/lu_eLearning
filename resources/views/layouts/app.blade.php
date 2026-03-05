<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>LU Academy - {{ $title ?? 'Dashboard' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.bunny.net/css?family=dm-sans:400,500,600,700&display=swap" rel="stylesheet">
    <style>
        :root { --lu-deep-purple: #2d1b4e; --lu-purple: #4c1d95; }
        body { font-family: 'DM Sans', sans-serif; background: #f8f7fc; }
        .navbar-lu { background: #fff !important; border-bottom: 1px solid rgba(45,27,78,0.1); }
        .nav-link.active { color: var(--lu-deep-purple) !important; font-weight: 600; border-bottom: 2px solid var(--lu-deep-purple); }
        .btn-lu-primary { background: var(--lu-deep-purple); color: #fff; border: none; }
        .btn-lu-primary:hover { background: var(--lu-purple); color: #fff; }
    </style>
    @stack('styles')
</head>
<body>
    @include('layouts.navigation')

    @isset($header)
        <header class="bg-white border-bottom py-4">
            <div class="container">
                {{ $header }}
            </div>
        </header>
    @endisset

    <main class="py-4">
        {{ $slot }}
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @vite(['resources/js/app.js'])
    @stack('scripts')
</body>
</html>
