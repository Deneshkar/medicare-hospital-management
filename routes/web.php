<?php

use App\Http\Controllers\Web\AiAssistantController;
use App\Http\Controllers\Web\AppointmentController;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\DepartmentController;
use App\Http\Controllers\Web\DoctorController;
use App\Http\Controllers\Web\InvoiceController;
use App\Http\Controllers\Web\LabReportController;
use App\Http\Controllers\Web\MedicalRecordController;
use App\Http\Controllers\Web\NotificationController;
use App\Http\Controllers\Web\PatientController;
use App\Http\Controllers\Web\PrescriptionController;
use App\Http\Controllers\Web\VitalSignController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::name('web.')->group(function () {
        Route::middleware('role:admin')->group(function () {
            Route::resource('departments', DepartmentController::class);
            Route::resource('doctors', DoctorController::class);
        });

        Route::resource('patients', PatientController::class);
        Route::resource('appointments', AppointmentController::class)->except(['update']);
        Route::patch('/appointments/{appointment}/status', [AppointmentController::class, 'updateStatus'])
            ->name('appointments.status');
        Route::resource('medical-records', MedicalRecordController::class)
            ->parameters(['medical-records' => 'medicalRecord']);
        Route::resource('vital-signs', VitalSignController::class)->only(['index', 'create', 'store', 'show'])
            ->parameters(['vital-signs' => 'vitalSign']);
        Route::get('/prescriptions/{prescription}/pdf', [PrescriptionController::class, 'downloadPdf'])
            ->name('prescriptions.pdf');
        Route::resource('prescriptions', PrescriptionController::class)->only(['index', 'create', 'store', 'show']);
        Route::get('/lab-reports/{labReport}/download', [LabReportController::class, 'download'])
            ->name('lab-reports.download');
        Route::get('/lab-reports/{labReport}/edit', [LabReportController::class, 'edit'])
            ->name('lab-reports.edit');
        Route::post('/lab-reports/{labReport}', [LabReportController::class, 'update'])
            ->name('lab-reports.update');
        Route::resource('lab-reports', LabReportController::class)->only(['index', 'create', 'store', 'show'])
            ->parameters(['lab-reports' => 'labReport']);
        Route::get('/invoices/{invoice}/pdf', [InvoiceController::class, 'downloadPdf'])
            ->name('invoices.pdf');
        Route::patch('/invoices/{invoice}/payment', [InvoiceController::class, 'recordPayment'])
            ->name('invoices.payment');
        Route::resource('invoices', InvoiceController::class)->only(['index', 'create', 'store', 'show']);
        Route::get('/notifications', [NotificationController::class, 'index'])
            ->name('notifications.index');
        Route::patch('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])
            ->name('notifications.read-all');
        Route::patch('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])
            ->name('notifications.read');
        Route::get('/ai', [AiAssistantController::class, 'index'])->name('ai.index');
        Route::post('/ai/chat', [AiAssistantController::class, 'chat'])->name('ai.chat');
    });
});
