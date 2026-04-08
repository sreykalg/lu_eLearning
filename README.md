# LU Academy Learning Platform

A role-based learning platform built with Laravel, used by students, instructors, HoD, and admin to manage courses, lessons, quizzes, assignments, announcements, discussions, and grading.

## Tech Stack

- PHP 8.2
- Laravel 12
- MySQL
- Blade + Bootstrap (primary UI framework)
- Vite
- Tailwind tooling available (not primary runtime styling layer)

## Core Modules

- Authentication + role-based dashboards
- Course catalog and enrollment
- Course builder (modules, lessons, quizzes, assignments)
- Student learning flow (watch lessons, take quizzes, submit assignments)
- Progress, grades, and points
- Announcements and discussions
- HoD review/approval and monitoring tools
- Enrollment archive workflow (`archived_at`) for historical retention

## Recent Product Behavior

- Enrollments support archive/unarchive via `archived_at` (instead of hard delete).
- Student/instructor/HoD counts use active enrollments where appropriate.
- Instructor can toggle course publish state (for approved courses).
- Lesson video upload supports MP4/MOV/WebM with server-side compatibility handling.
- Lesson page includes in-video quiz checkpoints and timeline marker support.
- UI has been refreshed across multiple student/instructor/HoD screens.

## Local Setup

1. Clone the repository
2. Install backend dependencies
3. Configure environment
4. Run migrations
5. Install frontend dependencies
6. Start app

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
npm install
npm run dev
php artisan serve
```

Or use the provided composer helper:

```bash
composer run setup
```

## Environment Notes

- Database defaults in `.env.example`:
  - `DB_CONNECTION=mysql`
  - `DB_DATABASE=lu_learn`
- Files and uploads:
  - Ensure storage symlink is available:

```bash
php artisan storage:link
```

- For assignment deadline correctness, set timezone in `.env`:
  - `APP_TIMEZONE=Asia/Kuala_Lumpur` (or your local timezone)

## Video Compatibility Note

For best production playback compatibility:

- Prefer MP4 (H.264/AAC) uploads
- MOV is accepted; server-side handling may convert for web playback
- Ensure `ffmpeg` is installed on the deployment environment when conversion is required

## Useful Commands

```bash
# Run tests
php artisan test

# Clear config/cache
php artisan optimize:clear

# Build assets
npm run build

# Lint/format PHP
./vendor/bin/pint
```

## Project Structure (high level)

- `app/Http/Controllers` – request handlers by domain/role
- `app/Models` – Eloquent models and scopes
- `resources/views` – Blade UI templates
- `routes/web.php` – web routes
- `database/migrations` – schema evolution

## License

This project is currently maintained as an internal academy platform.  
If you plan to open-source it, add your preferred license here.
