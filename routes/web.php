<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AttendanceController;
use App\Http\Controllers\Admin\SettingController;

// Web Authentication
Route::get('/', function(){ return redirect()->route('login'); });
Route::get('login', [App\Http\Controllers\Admin\AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [App\Http\Controllers\Admin\AuthController::class, 'login'])->name('do_login');
Route::post('logout', [App\Http\Controllers\Admin\AuthController::class, 'logout'])->name('logout');

// Admin protected routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function(){ return redirect()->route('admin.dashboard'); });
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Users management
    Route::resource('users', UserController::class);
    Route::post('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
    
    // Attendances management
    Route::get('attendances', [AttendanceController::class, 'index'])->name('attendances.index');
    Route::get('attendances/report', [AttendanceController::class, 'report'])->name('attendances.report');
    Route::get('attendances/export', [AttendanceController::class, 'export'])->name('attendances.export');
    Route::get('attendances/{attendance}', [AttendanceController::class, 'show'])->name('attendances.show');
    
    // Settings
    Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('settings', [SettingController::class, 'update'])->name('settings.update');
});
