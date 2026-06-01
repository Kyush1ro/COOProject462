<?php


use App\Http\Controllers\ProgressController;

use App\Http\Controllers\CourseController;

use App\Http\Controllers\UserController;

use App\Http\Controllers\AssignmentController;

use App\Http\Controllers\MaterialController;

use App\Http\Controllers\EnrollmentController;

use App\Http\Controllers\SubmissionController;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\SemesterController;
use App\Http\Controllers\NoticeController;

use App\Http\Controllers\ChatbotController;

use App\Http\Controllers\NotificationController;

use App\Http\Controllers\StatisticsController;

use App\Http\Controllers\Traits\LogsActivity;

use App\Http\Controllers\AuditLogController;

// -------------------------------

use App\Models\Assignment;

use App\Models\User;

use App\Models\Course;

use App\Models\Progress;

use App\Models\AuditLog;

use Illuminate\Support\Facades\Route;

use Illuminate\Support\Facades\Auth;



/*

|--------------------------------------------------------------------------

| Web Routes

|--------------------------------------------------------------------------

*/

Route::get('lang/{locale}', function ($locale) {
    if (! in_array($locale, ['en', 'ar'])) {
        abort(400);
    }

    session(['locale' => $locale]);

    return redirect()->back();
})->name('lang.switch');

Route::get('/', function () {

    return view('welcome');
});



Route::get('/test', function () {

    if (!Auth::check()) {

        return "Please log in first to test this page!";
    }

    $courses = \App\Models\Course::all();

    return view('test', compact('courses'));
});



Route::middleware(['auth', 'verified'])->group(function () {



    Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');



    // Profile Routes

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');



    // Main Routes
 Route::get('/courses/available', [CourseController::class, 'available'])->name('courses.available');

    Route::resource('courses', CourseController::class);
    Route::resource('users', UserController::class);
    Route::resource('departments', DepartmentController::class);
    Route::resource('semesters', SemesterController::class);
    
    // Global Notices / Notifications
    Route::get('/notifications', [NoticeController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [NoticeController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NoticeController::class, 'markAllAsRead'])->name('notifications.readAll');
    
    // Admin Routes for sending notices
    Route::get('/admin/notices/create', [NoticeController::class, 'create'])->name('notifications.create');
    Route::post('/admin/notices', [NoticeController::class, 'store'])->name('notifications.store');
    // Reports
    Route::get('/admin/reports', [App\Http\Controllers\ReportController::class, 'index'])->name('admin.reports.index');

    Route::resource('assignments', AssignmentController::class);

    Route::resource('materials', MaterialController::class);

    Route::resource('enrollments', EnrollmentController::class);

    Route::resource('submissions', SubmissionController::class);

    Route::get('/submissions/{submission}/download', [SubmissionController::class, 'download'])->name('submissions.download');

    Route::get('/submissions/{submission}/feedback/download', [SubmissionController::class, 'downloadFeedback'])->name('submissions.feedback.download');

    Route::resource('announcements', \App\Http\Controllers\AnnouncementController::class)->only(['index', 'store', 'destroy']);

    Route::resource('departments', \App\Http\Controllers\DepartmentController::class);

    Route::get('/courses/department/{code}', [CourseController::class, 'index'])->name('courses.byDepartment');

    Route::resource('logs', AuditLogController::class)->only(['index', 'destroy']);



    // --- CUSTOM ACTIONS ---



    // 1. Enroll & Drop

    Route::post('/courses/{course}/enroll', [EnrollmentController::class, 'store'])->name('courses.enroll');

    Route::delete('/courses/{course}/drop', [EnrollmentController::class, 'destroy'])->name('courses.drop');



    // 2. Materials Upload

    Route::post('/courses/{course}/materials', [MaterialController::class, 'store'])->name('courses.materials.store');

    Route::get('/materials/{material}/download', [MaterialController::class, 'download'])->name('materials.download');



    // 3. Student Progress

    Route::get('/my-progress', [ProgressController::class, 'index'])->name('progress.index');

    Route::post('/courses/{course}/progress', [ProgressController::class, 'update'])->name('courses.progress.update');



    // --- DAY 6: CHATBOT & NOTIFICATIONS ---

    Route::post('/chatbot/send', [ChatbotController::class, 'sendMessage'])->name('chatbot.send');

    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');

    Route::post('/notifications/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');



    // ==========================================================================

    // SIDEBAR ROUTES

    // ==========================================================================



    Route::prefix('student')->name('student.')->group(function () {

        Route::get('assignments', fn() => redirect()->route('assignments.index'))->name('assignments.index');

        Route::get('grades', [\App\Http\Controllers\GradeController::class, 'index'])->name('grades.index');

        Route::get('calendar', [\App\Http\Controllers\CalendarController::class, 'index'])->name('calendar.index');

        Route::get('attendance', [\App\Http\Controllers\AttendanceController::class, 'studentIndex'])->name('attendance.index');
    });



    Route::prefix('instructor')->name('instructor.')->group(function () {

        Route::get('materials', fn() => redirect()->route('materials.index'))->name('materials.index');

        Route::get('gradebook', [\App\Http\Controllers\GradebookController::class, 'index'])->name('gradebook.index');

        Route::get('announcements', [\App\Http\Controllers\AnnouncementController::class, 'index'])->name('announcements.index');
    });



    Route::prefix('teacher')->name('teacher.')->group(function () {

        Route::get('students', [\App\Http\Controllers\StudentListController::class, 'index'])->name('students.index');

        Route::get('attendance', [\App\Http\Controllers\AttendanceController::class, 'index'])->name('attendance.index');

        Route::post('attendance', [\App\Http\Controllers\AttendanceController::class, 'store'])->name('attendance.store');

        Route::get('assignments', fn() => redirect()->route('assignments.index'))->name('assignments.index');
        
        Route::get('submissions', fn() => redirect()->route('submissions.index'))->name('submissions.index');
    });



    Route::prefix('admin')->name('admin.')->group(function () {

        Route::get('users', fn() => 'User List')->name('users.index');

        Route::get('users/create', fn() => 'Create User')->name('users.create');

        Route::get('courses', [CourseController::class, 'index'])->name('courses.index');

        Route::get('courses/create', [CourseController::class, 'create'])->name('courses.create');

        Route::get('terms', fn() => 'Semesters')->name('terms.index');

        Route::get('departments', fn() => 'Departments')->name('departments.index');

        Route::get('announcements', fn() => 'Global Notices')->name('announcements.index');



        // --- DAY 7: REPORTS ROUTE ---

        Route::get('reports', [StatisticsController::class, 'index'])->name('reports.index');



        Route::get('settings', [\App\Http\Controllers\SettingController::class, 'index'])->name('settings.index');
        Route::post('settings', [\App\Http\Controllers\SettingController::class, 'store'])->name('settings.store');
    });
});



require __DIR__ . '/auth.php';
