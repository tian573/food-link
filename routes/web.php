<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\AdminController;

Route::get('/', [HomeController::class, 'my_home']);
Route::get('/map', [HomeController::class, 'map']);

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {
    Route::get('/form', [DonationController::class, 'showUpload'])->name('donation.upload');
    Route::post('/form/analyze', [DonationController::class, 'analyzePhoto'])->name('donation.analyze');
    Route::get('/form/donation', [DonationController::class, 'showForm'])->name('donation.form');
    Route::post('/form/donation', [DonationController::class, 'store'])->name('donation.store');
    Route::get('/donation/success', [DonationController::class, 'success'])->name('donation.success');
    Route::get('/my-donations', [DonationController::class, 'myDonations'])->name('donation.myDonations');
    Route::patch('/donations/{id}/status', [DonationController::class, 'updateStatus'])->name('donation.updateStatus');
    
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'delete'])->name('profile.delete');
    
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified', 'admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::patch('/admin/donations/{id}/status', [AdminController::class, 'updateDonationStatus'])->name('admin.donations.updateStatus');
    Route::delete('/admin/donations/{id}', [AdminController::class, 'deleteDonation'])->name('admin.donations.delete');
    Route::delete('/admin/users/{id}', [AdminController::class, 'deleteUser'])->name('admin.users.delete');
});

Route::get('/home', [HomeController::class, 'index']);