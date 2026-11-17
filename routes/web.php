<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\ProfileController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Member1_Rifaat\AlertController;
use App\Http\Controllers\Member1_Rifaat\EligibilityController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public Landing Page
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Guest Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store'])->name('register.store');

    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->name('login.store');
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [LogoutController::class, 'destroy'])->name('logout');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');

    // Feature R1: Donor Health Evaluation Engine
    Route::get('/donor/eligibility', [EligibilityController::class, 'index'])->name('donor.eligibility');
    Route::post('/donor/eligibility', [EligibilityController::class, 'evaluate'])->name('donor.eligibility.evaluate');

    // Feature R8: Automated Expiry & Low-Stock Alerts
    Route::get('/inventory/alerts', [AlertController::class, 'index'])->name('inventory.alerts');
    Route::post('/inventory/alerts/scan', [AlertController::class, 'scan'])->name('inventory.alerts.scan');
    Route::post('/inventory/alerts/{id}/resolve', [AlertController::class, 'resolve'])->name('inventory.alerts.resolve');
    Route::post('/inventory/alerts/seed', [AlertController::class, 'seedSampleUnits'])->name('inventory.alerts.seed');
});
