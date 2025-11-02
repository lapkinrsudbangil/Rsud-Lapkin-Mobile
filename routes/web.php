<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\ProfileController; 

// Public routes
Route::middleware('guest')->group(function () {
    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.post');
    
    Route::get('/register', [RegisterController::class, 'showForm'])->name('register.form');
    Route::post('/register', [RegisterController::class, 'store'])->name('register.post');
});

// Protected routes
Route::middleware(['auth'])->group(function () {
    Route::get('/', function () {
        return view('home');
    })->name('home');
    
    Route::get('/dashboard/direktur', function () {
        return view('dashboard.direktur');
    })->name('dashboard.direktur');
    
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    
    // Perjanjian & Laporan
    Route::get('/perjanjian', [App\Http\Controllers\PerjanjianController::class, 'index'])->name('perjanjian.index');
    Route::get('/perjanjian/create', [App\Http\Controllers\PerjanjianController::class, 'create'])->name('perjanjian.create');
    Route::post('/perjanjian', [App\Http\Controllers\PerjanjianController::class, 'store'])->name('perjanjian.store');

    Route::get('/laporan', [App\Http\Controllers\LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/create/{id}', [App\Http\Controllers\LaporanController::class, 'createFromPerjanjian'])->name('laporan.create_from_perjanjian');
    Route::post('/laporan', [App\Http\Controllers\LaporanController::class, 'store'])->name('laporan.store');
    Route::get('/laporan/my', [App\Http\Controllers\LaporanController::class, 'myReports'])->name('laporan.my');
});

// === LOGIN ===
Route::get('/login', function () {
    return view('auth.login');
})->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');

// === REGISTER ===
Route::get('/register', [RegisterController::class, 'showForm'])->name('register.form');
Route::post('/register', [RegisterController::class, 'store'])->name('register.post');

// === FORGOT PASSWORD ===
Route::get('/forgot-password', [ForgotPasswordController::class, 'showForgotForm'])->name('forgot.form');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendCode'])->name('forgot.post');

Route::get('/verify-code', [ForgotPasswordController::class, 'showVerifyForm'])->name('verify.form');
Route::post('/verify-code', [ForgotPasswordController::class, 'verifyCode'])->name('verify.code');

// === RESET PASSWORD ===
Route::get('/reset-password', [ForgotPasswordController::class, 'showResetForm'])->name('reset.form');
Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword'])->name('reset.post');

// === PROFIL ===
Route::middleware(['auth'])->group(function() {
    Route::get('/profil', [ProfileController::class, 'index'])->name('profil');
    Route::post('/profil/update', [ProfileController::class, 'update'])->name('profil.update');
});
