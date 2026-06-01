<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiController;

// Standard User Route
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Public Routes
Route::post('/login', [ApiController::class, 'login']);

// Day 5 API Routes - Protected by Authentication
Route::middleware('auth:sanctum')->group(function () {
    
    // GET /courses
    Route::get('/courses', [ApiController::class, 'index']);

    // GET /course/{id}
    Route::get('/course/{id}', [ApiController::class, 'show']);

    // POST /progress/update
    Route::post('/progress/update', [ApiController::class, 'updateProgress']);

    // GET /student/performance
    Route::get('/student/performance', [ApiController::class, 'studentPerformance']);

    // POST /enroll
    Route::post('/enroll', [ApiController::class, 'enroll']);

    // POST /submission
    Route::post('/submission', [ApiController::class, 'submitAssignment']);

    // POST /announcement
    Route::post('/announcement', [ApiController::class, 'createAnnouncement']);

    // GET /assignments
    Route::get('/assignments', [ApiController::class, 'getAssignments']);

    // GET /materials
    Route::get('/materials', [ApiController::class, 'getMaterials']);

    // GET /departments
    Route::get('/departments', [ApiController::class, 'getDepartments']);

    // GET /semesters
    Route::get('/semesters', [ApiController::class, 'getSemesters']);

});