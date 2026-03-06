<?php

use App\Http\Controllers\Admin\CourseController as AdminCourseController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\DiscussionController;
use App\Http\Controllers\Instructor\AssignmentController;
use App\Http\Controllers\Instructor\CourseController as InstructorCourseController;
use App\Http\Controllers\Instructor\DashboardController;
use App\Http\Controllers\Instructor\LessonController as InstructorLessonController;
use App\Http\Controllers\Instructor\QuizController as InstructorQuizController;
use App\Http\Controllers\Instructor\VideoQuizController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
Route::get('/courses/{course:slug}', [CourseController::class, 'show'])->name('courses.show');
Route::get('/discussions', [DiscussionController::class, 'index'])->name('discussions.index');
Route::get('/discussions/{discussion}', [DiscussionController::class, 'show'])->name('discussions.show');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        if (auth()->user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        if (auth()->user()->isInstructor()) {
            return redirect()->route('instructor.dashboard');
        }
        return view('dashboard');
    })->name('dashboard');

    Route::post('/courses/{course:slug}/enroll', [CourseController::class, 'enroll'])->name('courses.enroll');
    Route::get('/courses/{course:slug}/lessons/{lesson}', [LessonController::class, 'show'])->name('lessons.show');
    Route::post('/lessons/progress', [LessonController::class, 'updateProgress'])->name('lessons.progress');

    Route::post('/discussions', [DiscussionController::class, 'store'])->name('discussions.store');
    Route::post('/discussions/reply', [DiscussionController::class, 'reply'])->name('discussions.reply');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'verified', 'instructor'])->prefix('instructor')->name('instructor.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('courses', InstructorCourseController::class)->except(['show'])->parameters(['courses' => 'course']);
    Route::get('courses/{course}/lessons/create', [InstructorLessonController::class, 'create'])->name('lessons.create');
    Route::post('courses/{course}/lessons', [InstructorLessonController::class, 'store'])->name('lessons.store');
    Route::get('courses/{course}/lessons/{lesson}/edit', [InstructorLessonController::class, 'edit'])->name('lessons.edit');
    Route::put('courses/{course}/lessons/{lesson}', [InstructorLessonController::class, 'update'])->name('lessons.update');
    Route::delete('courses/{course}/lessons/{lesson}', [InstructorLessonController::class, 'destroy'])->name('lessons.destroy');
    Route::post('video-quizzes', [VideoQuizController::class, 'store'])->name('video-quizzes.store');
    Route::put('video-quizzes/{videoQuiz}', [VideoQuizController::class, 'update'])->name('video-quizzes.update');
    Route::delete('video-quizzes/{videoQuiz}', [VideoQuizController::class, 'destroy'])->name('video-quizzes.destroy');
    Route::get('courses/{course}/quizzes/create', [InstructorQuizController::class, 'create'])->name('quizzes.create');
    Route::post('courses/{course}/quizzes', [InstructorQuizController::class, 'store'])->name('quizzes.store');
    Route::get('courses/{course}/quizzes/{quiz}/edit', [InstructorQuizController::class, 'edit'])->name('quizzes.edit');
    Route::put('courses/{course}/quizzes/{quiz}', [InstructorQuizController::class, 'update'])->name('quizzes.update');
    Route::delete('courses/{course}/quizzes/{quiz}', [InstructorQuizController::class, 'destroy'])->name('quizzes.destroy');
    Route::get('courses/{course}/assignments/create', [AssignmentController::class, 'create'])->name('assignments.create');
    Route::post('courses/{course}/assignments', [AssignmentController::class, 'store'])->name('assignments.store');
    Route::get('courses/{course}/assignments/{assignment}/edit', [AssignmentController::class, 'edit'])->name('assignments.edit');
    Route::put('courses/{course}/assignments/{assignment}', [AssignmentController::class, 'update'])->name('assignments.update');
    Route::delete('courses/{course}/assignments/{assignment}', [AssignmentController::class, 'destroy'])->name('assignments.destroy');
});

Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('/courses', [AdminCourseController::class, 'index'])->name('courses.index');
});

require __DIR__.'/auth.php';
