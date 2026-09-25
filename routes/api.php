<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DepartmentController;
use App\Http\Controllers\Api\DoctorController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PatientController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    Route::middleware('role:admin')->group(function () {
        Route::apiResource('departments', DepartmentController::class);
        Route::apiResource('doctors', DoctorController::class);
    });

    Route::get('/departments', [DepartmentController::class, 'index']);
    Route::get('/doctors', [DoctorController::class, 'index']);
    Route::apiResource('patients', PatientController::class);
});
