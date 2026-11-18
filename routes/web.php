<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\ProfileController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Member1_Rifaat\AlertController;
use App\Http\Controllers\Member1_Rifaat\EligibilityController;
use App\Http\Controllers\Member4_Reehay\BloodRequestController;
use App\Http\Controllers\Member4_Reehay\SOSController;
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

    // Feature R4: Blood Request Tracking (Member4_Reehay)
    Route::get('/blood/requests', [BloodRequestController::class, 'index'])->name('blood.requests');
    Route::post('/blood/requests', [BloodRequestController::class, 'store'])->name('blood.requests.store');
    Route::post('/blood/requests/{id}/fulfill', [BloodRequestController::class, 'fulfill'])->name('blood.requests.fulfill');
    Route::post('/blood/requests/{id}/expire', [BloodRequestController::class, 'expire'])->name('blood.requests.expire');

    // Feature R5: Emergency Escalation (Member4_Reehay)
    Route::get('/emergency/broadcast', [SOSController::class, 'index'])->name('emergency.broadcast');
    Route::post('/emergency/{id}/activate', [SOSController::class, 'activate'])->name('emergency.activate');
    Route::post('/emergency/{id}/escalate', [SOSController::class, 'escalate'])->name('emergency.escalate');
    Route::post('/emergency/{id}/deactivate', [SOSController::class, 'deactivate'])->name('emergency.deactivate');
    Route::post('/emergency/create-sos', [SOSController::class, 'createSOS'])->name('emergency.create-sos');
});
