<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\RequestController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\BloodUnitController;

Route::get('/', function () {
    return view('auth.userlogin');
});

// Authentication routes
Route::get('/userregister', [AuthController::class, 'showRegister'])->name('userregister');
Route::post('/userregister', [AuthController::class, 'register'])->name('register');

Route::get('/userlogin', [AuthController::class, 'showLogin'])->name('userlogin');
Route::post('/userlogin', [AuthController::class, 'login'])->name('login');

Route::middleware('auth')->group(function () {
    Route::get('/userdashboard', [DashboardController::class, 'index'])->name('userdashboard');
    Route::post('/donation', [DonationController::class, 'store'])->name('donation.store');
    Route::post('/request', [RequestController::class, 'store'])->name('request.store');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::middleware('auth')->group(function () {
    Route::get('/staffdashboard', [DashboardController::class, 'staff'])->name('staffdashboard');
    Route::post('/staff/notify', [NotificationController::class, 'send'])->name('staff.notify.send');
    Route::post('/staff/unit', [BloodUnitController::class, 'store'])->name('staff.unit.store');
    Route::put('/staff/unit/{id}', [BloodUnitController::class, 'update'])->name('staff.unit.update');
    Route::post('/staff/logout', [AuthController::class, 'logout'])->name('staff.logout');
});

Route::middleware('auth')->group(function () {
    Route::get('/admindashboard', [DashboardController::class, 'admin'])->name('admindashboard');
    Route::post('/report/pdf', [AdminController::class, 'generatePdf'])->name('report.pdf');
    Route::post('/admin/logout', [AuthController::class, 'logout'])->name('admin.logout');
});


