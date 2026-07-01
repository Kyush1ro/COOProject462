<?php

use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\LeaveRequestController;
use App\Http\Controllers\MaintenanceRequestController;
use App\Http\Controllers\NoticeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PurchaseRequisitionController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\SparePartController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WorkOrderController;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Language Switch
|--------------------------------------------------------------------------
*/

Route::get('/lang/{locale}', function ($locale) {
    if (! in_array($locale, ['en', 'ar'])) {
        abort(400);
    }

    session(['locale' => $locale]);
    App::setLocale($locale);

    return redirect()->back();
})->name('lang.switch');

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | Profile
    |--------------------------------------------------------------------------
    */

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

    /*
    |--------------------------------------------------------------------------
    | Main System Management
    |--------------------------------------------------------------------------
    */

    Route::resource('users', UserController::class);
    Route::resource('departments', DepartmentController::class);

    /*
    |--------------------------------------------------------------------------
    | Petrochemical ERP Modules
    |--------------------------------------------------------------------------
    */

    // PM Module
    Route::resource('equipment', EquipmentController::class);
    Route::resource('maintenance-requests', MaintenanceRequestController::class);
    Route::resource('work-orders', WorkOrderController::class);

    // MM Module
    Route::resource('spare-parts', SparePartController::class);
    Route::resource('purchase-requisitions', PurchaseRequisitionController::class);

    // HCM Module
    Route::resource('leave-requests', LeaveRequestController::class);

    /*
    |--------------------------------------------------------------------------
    | Notifications / Notices
    |--------------------------------------------------------------------------
    */

    Route::get('/notifications', [NoticeController::class, 'index'])
        ->name('notifications.index');

    Route::post('/notifications/{id}/read', [NoticeController::class, 'markAsRead'])
        ->name('notifications.read');

    Route::post('/notifications/read-all', [NoticeController::class, 'markAllAsRead'])
        ->name('notifications.readAll');

    Route::get('/admin/notices/create', [NoticeController::class, 'create'])
        ->name('notifications.create');

    Route::post('/admin/notices', [NoticeController::class, 'store'])
        ->name('notifications.store');

    /*
    |--------------------------------------------------------------------------
    | Admin
    |--------------------------------------------------------------------------
    */

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::resource('logs', AuditLogController::class)->only(['index', 'destroy']);

        Route::get('settings', [SettingController::class, 'index'])
            ->name('settings.index');

        Route::post('settings', [SettingController::class, 'store'])
            ->name('settings.store');
    });
});

require __DIR__ . '/auth.php';