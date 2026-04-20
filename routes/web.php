<?php

use App\Http\Controllers\Admin\ApplicationController as AdminApplicationController;
use App\Http\Controllers\Admin\CandidateController as AdminCandidateController;
use App\Http\Controllers\Admin\ClientController as AdminClientController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\InterviewController as AdminInterviewController;
use App\Http\Controllers\Admin\PositionController as AdminPositionController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Candidate\ApplicationController as CandidateApplicationController;
use App\Http\Controllers\Candidate\DashboardController as CandidateDashboardController;
use App\Http\Controllers\Candidate\ProfileController as CandidateProfileController;
use App\Http\Controllers\Candidate\ReferralController as CandidateReferralController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PositionController;
use Illuminate\Support\Facades\Route;

// Public
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/positions', [PositionController::class, 'index'])->name('positions.index');
Route::get('/positions/{position}', [PositionController::class, 'show'])->name('positions.show');

// Guest routes (auth)
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->name('login.store');

    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store'])->name('register.store');

    Route::get('/forgot-password', [PasswordResetController::class, 'create'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetController::class, 'sendLink'])->name('password.email');

    Route::get('/reset-password', [PasswordResetController::class, 'edit'])->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'update'])->name('password.update');
});

Route::post('/logout', LogoutController::class)->middleware('auth')->name('logout');

// Candidate area
Route::middleware(['auth', 'role:candidate'])
    ->prefix('candidate')
    ->name('candidate.')
    ->group(function () {
        Route::get('/', [CandidateDashboardController::class, 'index'])->name('dashboard');

        Route::get('/profile', [CandidateProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [CandidateProfileController::class, 'update'])->name('profile.update');
        Route::post('/profile/cv', [CandidateProfileController::class, 'uploadCv'])->name('profile.cv.store');
        Route::delete('/profile/cv', [CandidateProfileController::class, 'deleteCv'])->name('profile.cv.destroy');
        Route::get('/profile/cv/download', [CandidateProfileController::class, 'downloadCv'])->name('profile.cv.download');
        Route::post('/profile/image', [CandidateProfileController::class, 'uploadProfileImage'])->name('profile.image.store');
        Route::delete('/profile/image', [CandidateProfileController::class, 'deleteProfileImage'])->name('profile.image.destroy');

        Route::get('/applications', [CandidateApplicationController::class, 'index'])->name('applications.index');
        Route::post('/applications', [CandidateApplicationController::class, 'store'])->name('applications.store');
        Route::get('/applications/{application}', [CandidateApplicationController::class, 'show'])->name('applications.show');

        Route::get('/referrals', [CandidateReferralController::class, 'index'])->name('referrals.index');
        Route::post('/referrals', [CandidateReferralController::class, 'store'])->name('referrals.store');
        Route::post('/referrals/{referral}/resend', [CandidateReferralController::class, 'resend'])->name('referrals.resend');
        Route::delete('/referrals/{referral}', [CandidateReferralController::class, 'destroy'])->name('referrals.destroy');
    });

// Admin area — HR Admin + Super Admin
Route::middleware(['auth', 'role:hr_admin,super_admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

        Route::get('/candidates', [AdminCandidateController::class, 'index'])->name('candidates.index');
        Route::get('/candidates/{candidate}', [AdminCandidateController::class, 'show'])->name('candidates.show');
        Route::get('/candidates/{candidate}/cv', [AdminCandidateController::class, 'downloadCv'])->name('candidates.cv');

        Route::get('/applications', [AdminApplicationController::class, 'index'])->name('applications.index');
        Route::get('/applications/{application}', [AdminApplicationController::class, 'show'])->name('applications.show');
        Route::put('/applications/{application}/status', [AdminApplicationController::class, 'updateStatus'])->name('applications.status');
        Route::post('/applications/{application}/notes', [AdminApplicationController::class, 'addNote'])->name('applications.notes.store');

        Route::get('/interviews', [AdminInterviewController::class, 'index'])->name('interviews.index');
        Route::post('/interviews', [AdminInterviewController::class, 'store'])->name('interviews.store');
        Route::put('/interviews/{interview}', [AdminInterviewController::class, 'update'])->name('interviews.update');
        Route::delete('/interviews/{interview}', [AdminInterviewController::class, 'destroy'])->name('interviews.destroy');

        Route::get('/positions', [AdminPositionController::class, 'index'])->name('positions.index');
        Route::get('/positions/create', [AdminPositionController::class, 'create'])->name('positions.create');
        Route::post('/positions', [AdminPositionController::class, 'store'])->name('positions.store');
        Route::get('/positions/{position}/edit', [AdminPositionController::class, 'edit'])->name('positions.edit');
        Route::put('/positions/{position}', [AdminPositionController::class, 'update'])->name('positions.update');
        Route::delete('/positions/{position}', [AdminPositionController::class, 'destroy'])->name('positions.destroy');
        Route::post('/positions/{position}/image', [AdminPositionController::class, 'uploadImage'])->name('positions.image.store');
        Route::delete('/positions/{position}/image', [AdminPositionController::class, 'deleteImage'])->name('positions.image.destroy');
        Route::post('/positions/{position}/company-logo', [AdminPositionController::class, 'uploadCompanyLogo'])->name('positions.logo.store');
        Route::delete('/positions/{position}/company-logo', [AdminPositionController::class, 'deleteCompanyLogo'])->name('positions.logo.destroy');
    });

// Super Admin only — Clients + admin-user management
Route::middleware(['auth', 'role:super_admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/clients', [AdminClientController::class, 'index'])->name('clients.index');
        Route::get('/clients/create', [AdminClientController::class, 'create'])->name('clients.create');
        Route::post('/clients', [AdminClientController::class, 'store'])->name('clients.store');
        Route::get('/clients/{client}', [AdminClientController::class, 'show'])->name('clients.show');
        Route::get('/clients/{client}/edit', [AdminClientController::class, 'edit'])->name('clients.edit');
        Route::put('/clients/{client}', [AdminClientController::class, 'update'])->name('clients.update');
        Route::delete('/clients/{client}', [AdminClientController::class, 'destroy'])->name('clients.destroy');
        Route::post('/clients/{client}/logo', [AdminClientController::class, 'uploadLogo'])->name('clients.logo.store');
        Route::delete('/clients/{client}/logo', [AdminClientController::class, 'deleteLogo'])->name('clients.logo.destroy');

        Route::get('/admins', [AdminUserController::class, 'index'])->name('admins.index');
        Route::get('/admins/create', [AdminUserController::class, 'create'])->name('admins.create');
        Route::post('/admins', [AdminUserController::class, 'store'])->name('admins.store');
        Route::get('/admins/{user}/edit', [AdminUserController::class, 'edit'])->name('admins.edit');
        Route::put('/admins/{user}', [AdminUserController::class, 'update'])->name('admins.update');
        Route::delete('/admins/{user}', [AdminUserController::class, 'destroy'])->name('admins.destroy');
    });
