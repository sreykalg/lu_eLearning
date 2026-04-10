<?php

use App\Http\Controllers\Admin\CourseController as AdminCourseController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\DiscussionController;
use App\Http\Controllers\Hod\ApprovalController as HodApprovalController;
use App\Http\Controllers\Hod\DashboardController as HodDashboardController;
use App\Http\Controllers\Hod\ReportsController as HodReportsController;
use App\Http\Controllers\Hod\CourseMonitoringController as HodCourseMonitoringController;
use App\Http\Controllers\Hod\EnrollmentRenewalController as HodEnrollmentRenewalController;
use App\Http\Controllers\Hod\StudentEnrollmentController as HodStudentEnrollmentController;
use App\Http\Controllers\Hod\UserController as HodUserController;
use App\Http\Controllers\Instructor\AnnouncementController as InstructorAnnouncementController;
use App\Http\Controllers\Instructor\AssignmentController;
use App\Http\Controllers\Instructor\CourseController as InstructorCourseController;
use App\Http\Controllers\Instructor\DashboardController as InstructorDashboardController;
use App\Http\Controllers\Instructor\LessonController as InstructorLessonController;
use App\Http\Controllers\Instructor\ModuleController as InstructorModuleController;
use App\Http\Controllers\Instructor\ProgressController as InstructorProgressController;
use App\Http\Controllers\Instructor\QuizController as InstructorQuizController;
use App\Http\Controllers\Instructor\SubmissionsController as InstructorSubmissionsController;
use App\Http\Controllers\Instructor\VideoQuizController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OverviewController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Student\AssignmentController as StudentAssignmentController;
use App\Http\Controllers\Student\CourseController as StudentCourseController;
use App\Http\Controllers\Student\DashboardController as StudentDashboardController;
use App\Http\Controllers\Student\GradeController as StudentGradeController;
use App\Http\Controllers\Student\QuizController as StudentQuizController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
Route::get('/courses/{course:slug}', [CourseController::class, 'show'])->name('courses.show');
Route::get('/discussions', [DiscussionController::class, 'index'])->name('discussions.index');
Route::get('/discussions/{discussion}', [DiscussionController::class, 'show'])->name('discussions.show');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/overview', [OverviewController::class, 'index'])->name('overview');

    Route::get('/dashboard', function () {
        if (auth()->user()->isHeadOfDept()) {
            return redirect()->route('hod.dashboard');
        }
        if (auth()->user()->isInstructor()) {
            return redirect()->route('instructor.dashboard');
        }
        return redirect()->route('student.dashboard');
    })->name('dashboard');

    Route::prefix('student')->name('student.')->middleware('student')->group(function () {
        Route::get('/', [StudentDashboardController::class, 'index'])->name('dashboard');
        Route::get('/courses', [StudentCourseController::class, 'index'])->name('courses');
        Route::get('/assignments', [StudentAssignmentController::class, 'index'])->name('assignments');
        Route::get('/quizzes', [StudentQuizController::class, 'index'])->name('quizzes');
        Route::get('/grades', [StudentGradeController::class, 'index'])->name('grades');
    });

    Route::post('/courses/{course:slug}/enroll', [CourseController::class, 'enroll'])->name('courses.enroll');
    Route::get('/courses/{course:slug}/lessons/{lesson}', [LessonController::class, 'show'])->name('lessons.show');
    Route::get('/courses/{course:slug}/assignments/{assignment}', [StudentAssignmentController::class, 'show'])->name('student.assignments.show');
    Route::post('/courses/{course:slug}/assignments/{assignment}/submit', [StudentAssignmentController::class, 'submit'])->name('student.assignments.submit');
    Route::get('/courses/{course:slug}/quizzes/{quiz}', [StudentQuizController::class, 'show'])->name('student.quizzes.show');
    Route::post('/courses/{course:slug}/quizzes/{quiz}/submit', [StudentQuizController::class, 'submit'])->name('student.quizzes.submit');
    Route::get('/lesson-attachments/{attachment}/download', [LessonController::class, 'downloadAttachment'])->name('lesson-attachments.download');
    Route::post('/lessons/progress', [LessonController::class, 'updateProgress'])->name('lessons.progress');

    Route::get('/discussions/mention-users', [DiscussionController::class, 'mentionUsers'])->name('discussions.mention-users');
    Route::post('/discussions', [DiscussionController::class, 'store'])->name('discussions.store');
    Route::post('/discussions/{discussion}/like', [DiscussionController::class, 'like'])->name('discussions.like');
    Route::post('/discussions/replies/{reply}/like', [DiscussionController::class, 'replyLike'])->name('discussions.reply-like');
    Route::post('/discussions/reply', [DiscussionController::class, 'reply'])->name('discussions.reply');
    Route::post('/discussions/{discussion}/pin', [DiscussionController::class, 'pin'])->name('discussions.pin');
    Route::delete('/discussions/{discussion}', [DiscussionController::class, 'destroy'])->name('discussions.destroy');

    Route::get('/notifications/{id}/read', [NotificationController::class, 'read'])->name('notifications.read');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllRead'])->name('notifications.mark-all-read');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'verified', 'instructor'])->prefix('instructor')->name('instructor.')->group(function () {
    Route::get('/', [InstructorDashboardController::class, 'index'])->name('dashboard');
    Route::get('/my-courses', [InstructorCourseController::class, 'myCourses'])->name('my-courses');
    Route::delete('/my-courses/{course}/students/{student}', [InstructorCourseController::class, 'removeStudent'])->name('my-courses.students.remove');
    Route::get('/progress', [InstructorProgressController::class, 'index'])->name('progress');
    Route::get('/submissions', [InstructorSubmissionsController::class, 'index'])->name('submissions');
    Route::get('/submissions/{course}', [InstructorSubmissionsController::class, 'show'])->name('submissions.show');
    Route::get('/announcements', [InstructorAnnouncementController::class, 'index'])->name('announcements.index');
    Route::get('/announcements/create', [InstructorAnnouncementController::class, 'create'])->name('announcements.create');
    Route::post('/announcements', [InstructorAnnouncementController::class, 'store'])->name('announcements.store');
    Route::patch('/announcements/{announcement}', [InstructorAnnouncementController::class, 'update'])->name('announcements.update');
    Route::delete('/announcements/{announcement}', [InstructorAnnouncementController::class, 'destroy'])->name('announcements.destroy');
    Route::resource('courses', InstructorCourseController::class)->except(['show'])->parameters(['courses' => 'course']);
    Route::post('courses/{course}/submit-approval', [InstructorCourseController::class, 'submitForApproval'])->name('courses.submit-approval');
    Route::patch('courses/{course}/toggle-publish', [InstructorCourseController::class, 'togglePublish'])->name('courses.toggle-publish');
    Route::post('courses/{course}/modules', [InstructorModuleController::class, 'store'])->name('modules.store');
    Route::put('courses/{course}/modules/{module}', [InstructorModuleController::class, 'update'])->name('modules.update');
    Route::delete('courses/{course}/modules/{module}', [InstructorModuleController::class, 'destroy'])->name('modules.destroy');
    Route::post('courses/{course}/upload-video', [InstructorLessonController::class, 'uploadVideo'])->name('lessons.upload-video');
    Route::post('courses/{course}/upload-attachment', [InstructorLessonController::class, 'uploadAttachment'])->name('lessons.upload-attachment');
    Route::post('courses/{course}/upload-subtitle', [InstructorLessonController::class, 'uploadSubtitle'])->name('lessons.upload-subtitle');
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
    Route::get('courses/{course}/quizzes/{quiz}/attempts', [InstructorQuizController::class, 'attempts'])->name('quizzes.attempts');
    Route::get('courses/{course}/assignments/create', [AssignmentController::class, 'create'])->name('assignments.create');
    Route::post('courses/{course}/assignments', [AssignmentController::class, 'store'])->name('assignments.store');
    Route::get('courses/{course}/assignments/{assignment}/edit', [AssignmentController::class, 'edit'])->name('assignments.edit');
    Route::put('courses/{course}/assignments/{assignment}', [AssignmentController::class, 'update'])->name('assignments.update');
    Route::delete('courses/{course}/assignments/{assignment}', [AssignmentController::class, 'destroy'])->name('assignments.destroy');
    Route::get('courses/{course}/assignments/{assignment}/submissions', [AssignmentController::class, 'submissions'])->name('assignments.submissions');
    Route::put('courses/{course}/assignments/{assignment}/submissions/{submission}', [AssignmentController::class, 'gradeSubmission'])->name('assignments.submissions.grade');
});

Route::middleware(['auth', 'verified', 'head_of_dept'])->prefix('hod')->name('hod.')->group(function () {
    Route::get('/', [HodDashboardController::class, 'index'])->name('dashboard');
    Route::get('/monitoring', [HodCourseMonitoringController::class, 'index'])->name('monitoring.index');
    Route::get('/monitoring/{course}', [HodCourseMonitoringController::class, 'show'])->name('monitoring.show');
    Route::get('/monitoring/{course}/students/{student}', [HodCourseMonitoringController::class, 'student'])->name('monitoring.student');
    Route::get('/approval', [HodApprovalController::class, 'index'])->name('approval');
    Route::post('/approval/{course}/approve', [HodApprovalController::class, 'approve'])->name('approval.approve');
    Route::post('/approval/{course}/request-changes', [HodApprovalController::class, 'requestChanges'])->name('approval.request-changes');
    Route::get('/reports', [HodReportsController::class, 'index'])->name('reports');
    Route::get('/students', [HodStudentEnrollmentController::class, 'index'])->name('students.index');
    Route::post('/students/remove', [HodStudentEnrollmentController::class, 'remove'])->name('students.remove');
    Route::get('/students/{course}', [HodStudentEnrollmentController::class, 'show'])->name('students.show');
    Route::get('/enrollments/archive', [HodEnrollmentRenewalController::class, 'create'])->name('enrollments.archive');
    Route::post('/enrollments/archive', [HodEnrollmentRenewalController::class, 'store'])->name('enrollments.archive.store');
    Route::get('/users', [HodUserController::class, 'index'])->name('users');
    Route::get('/instructors', [HodUserController::class, 'instructors'])->name('instructors.index');
    Route::post('/instructors', [HodUserController::class, 'storeInstructor'])->name('instructors.store');
    Route::put('/instructors/{instructor}', [HodUserController::class, 'updateInstructor'])->name('instructors.update');
    Route::delete('/instructors/{instructor}', [HodUserController::class, 'destroyInstructor'])->name('instructors.destroy');
});

Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('/courses', [AdminCourseController::class, 'index'])->name('courses.index');
});

require __DIR__.'/auth.php';
