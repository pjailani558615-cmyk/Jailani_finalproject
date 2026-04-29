<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\RequestController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\AdminController;

Route::get('/', function () {
    return view('auth.userlogin');
});

// Authentication routes
Route::get('/userregister', [AuthController::class, 'showRegister'])->name('userregister');
Route::post('/userregister', [AuthController::class, 'register'])->name('register');

Route::get('/userlogin', [AuthController::class, 'showLogin'])->name('userlogin');
Route::post('/userlogin', [AuthController::class, 'login'])->name('login');

// Staff Registration
Route::get('/staff-register', [StaffController::class, 'showStaffRegister'])->name('staff.register.show');
Route::post('/staff-register', [StaffController::class, 'staffregister'])->name('staff.register');

Route::get('/staff-login', [StaffController::class, 'showStaffLogin'])->name('staff.login.show');
Route::post('/staff-login', [StaffController::class, 'stafflogin'])->name('staff.login');

// Admin Registration
Route::get('/admin-register', [AuthController::class, 'showAdminRegister'])->name('admin.register.show');
Route::post('/admin-register', [AuthController::class, 'adminRegister'])->name('admin.register');

Route::get('/admin-login', [AdminController::class, 'showAdminLogin'])->name('admin.login.show');
Route::post('/admin-login', [AdminController::class, 'adminLogin'])->name('admin.login');

Route::middleware('auth')->group(function () {
    Route::get('/userdashboard', [DashboardController::class, 'index'])->name('userdashboard');
    Route::post('/donation', [DonationController::class, 'store'])->name('donation.store');
    Route::post('/request', [RequestController::class, 'store'])->name('request.store');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::middleware('auth')->group(function () {
    Route::get('/staffdashboard', [StaffController::class, 'showStaffDashboard'])->name('staff.dashboard');
    Route::post('/staff/notify', [StaffController::class, 'sendNotification'])->name('staff.notify.send');
    Route::post('/staff/unit', [StaffController::class, 'storeBloodUnit'])->name('staff.unit.store');
    Route::post('/staff/logout', [StaffController::class, 'stafflogout'])->name('staff.logout');
});

Route::middleware('auth')->group(function () {
    Route::get('/admindashboard', [AdminController::class, 'index'])->name('admindashboard');
    Route::post('/report/pdf', [AdminController::class, 'generatePdf'])->name('report.pdf');
    Route::post('/admin/logout', [AdminController::class, 'adminLogout'])->name('admin.logout');
});


