<?php

use App\Http\Controllers\Api\AiAssistantController;
use App\Http\Controllers\Api\AppointmentController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\DepartmentController;
use App\Http\Controllers\Api\DoctorController;
use App\Http\Controllers\Api\InvoiceController;
use App\Http\Controllers\Api\LabReportController;
use App\Http\Controllers\Api\MedicalRecordController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\PatientController;
use App\Http\Controllers\Api\PrescriptionController;
use App\Http\Controllers\Api\VitalSignController;
use Illuminate\Support\Facades\Route;

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
    Route::apiResource('appointments', AppointmentController::class)->except(['update']);
    Route::patch('/appointments/{appointment}/status', [AppointmentController::class, 'updateStatus']);
    Route::apiResource('medical-records', MedicalRecordController::class)
        ->parameters(['medical-records' => 'medicalRecord']);
    Route::get('/vital-signs', [VitalSignController::class, 'index']);
    Route::post('/vital-signs', [VitalSignController::class, 'store']);
    Route::get('/vital-signs/{vitalSign}', [VitalSignController::class, 'show']);
    Route::get('/prescriptions', [PrescriptionController::class, 'index']);
    Route::post('/prescriptions', [PrescriptionController::class, 'store']);
    Route::get('/prescriptions/{prescription}/pdf', [PrescriptionController::class, 'downloadPdf']);
    Route::get('/prescriptions/{prescription}', [PrescriptionController::class, 'show']);
    Route::get('/lab-reports', [LabReportController::class, 'index']);
    Route::post('/lab-reports', [LabReportController::class, 'store']);
    Route::get('/lab-reports/{labReport}/download', [LabReportController::class, 'download']);
    Route::get('/lab-reports/{labReport}', [LabReportController::class, 'show']);
    Route::post('/lab-reports/{labReport}', [LabReportController::class, 'update']);
    Route::get('/invoices', [InvoiceController::class, 'index']);
    Route::post('/invoices', [InvoiceController::class, 'store']);
    Route::get('/invoices/{invoice}/pdf', [InvoiceController::class, 'downloadPdf']);
    Route::get('/invoices/{invoice}', [InvoiceController::class, 'show']);
    Route::patch('/invoices/{invoice}/payment', [InvoiceController::class, 'recordPayment']);
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::get('/notifications/unread-count', [NotificationController::class, 'unreadCount']);
    Route::patch('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);
    Route::patch('/notifications/read-all', [NotificationController::class, 'markAllAsRead']);
    Route::get('/dashboard/stats', [DashboardController::class, 'stats']);
    Route::post('/ai/chat', [AiAssistantController::class, 'chat']);
    Route::get('/ai/history', [AiAssistantController::class, 'history']);
});
