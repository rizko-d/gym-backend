<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\MemberController;
use App\Http\Controllers\API\TrainerController;
use App\Http\Controllers\API\GymClassController;
use App\Http\Controllers\API\ClassScheduleController;
use App\Http\Controllers\API\AttendanceController;

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Authentication
    Route::post('/logout', [AuthController::class, 'logout']);
    
    // Members
    Route::apiResource('members', MemberController::class);
    
    // Trainers
    Route::apiResource('trainers', TrainerController::class);
    
    // Gym Classes
    Route::apiResource('gym-classes', GymClassController::class);
    
    // Class Schedules
    Route::apiResource('class-schedules', ClassScheduleController::class);
    Route::post('/class-schedules/{classSchedule}/book', [ClassScheduleController::class, 'bookClass']);
    Route::delete('/class-schedules/{classSchedule}/cancel', [ClassScheduleController::class, 'cancelBooking']);
    
    // Attendance
    Route::post('/attendance/check-in', [AttendanceController::class, 'checkIn']);
    Route::post('/attendance/check-out', [AttendanceController::class, 'checkOut']);
    Route::get('/attendance/history', [AttendanceController::class, 'getAttendanceHistory']);
});
