<?php

use App\Http\Controllers\Admin\ApplicationController as AdminApplicationController;
use App\Http\Controllers\Admin\CandidateController;
use App\Http\Controllers\Admin\InterviewController;
use App\Http\Controllers\Admin\PositionController as AdminPositionController;
use App\Http\Controllers\Admin\StatsController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\MeController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Candidate\ApplicationController;
use App\Http\Controllers\Candidate\ProfileController;
use App\Http\Controllers\Candidate\ReferralController;
use App\Http\Controllers\PositionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Public routes
Route::get('/', function () {
    return response()->json([
        'name' => config('app.name'),
        'version' => '1.0.0',
    ]);
});

// Status endpoint for testing connectivity and CSRF
Route::get('/status', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now()->toIso8601String(),
        'app' => config('app.name'),
        'environment' => config('app.env'),
    ]);
});

// Auth routes
Route::prefix('auth')->group(function () {
    Route::post('/register', RegisterController::class);
    Route::post('/login', LoginController::class);
    Route::post('/forgot-password', [PasswordResetController::class, 'forgotPassword']);
    Route::post('/reset-password', [PasswordResetController::class, 'resetPassword']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', LogoutController::class);
        Route::get('/me', MeController::class);
    });
});

// Public positions
Route::prefix('positions')->group(function () {
    Route::get('/', [PositionController::class, 'index']);
    Route::get('/{position}', [PositionController::class, 'show']);
});

// Candidate routes (protected)
Route::middleware(['auth:sanctum', 'role:candidate'])->prefix('candidate')->group(function () {
    // Profile
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::put('/profile', [ProfileController::class, 'update']);
    Route::post('/profile/cv', [ProfileController::class, 'uploadCv']);
    Route::delete('/profile/cv', [ProfileController::class, 'deleteCv']);
    Route::get('/profile/cv', [ProfileController::class, 'downloadCv']);
    Route::post('/profile/image', [ProfileController::class, 'uploadProfileImage']);
    Route::delete('/profile/image', [ProfileController::class, 'deleteProfileImage']);

    // Applications
    Route::get('/applications', [ApplicationController::class, 'index']);
    Route::post('/applications', [ApplicationController::class, 'store']);
    Route::get('/applications/{application}', [ApplicationController::class, 'show']);

    // Referrals
    Route::get('/referrals', [ReferralController::class, 'index']);
    Route::post('/referrals', [ReferralController::class, 'store']);
});

// Admin routes (JAE Staff only)
Route::middleware(['auth:sanctum', 'role:jae_staff'])->prefix('admin')->group(function () {
    // Candidates
    Route::get('/candidates', [CandidateController::class, 'index']);
    Route::get('/candidates/{candidate}', [CandidateController::class, 'show']);
    Route::get('/candidates/{candidate}/cv', [CandidateController::class, 'downloadCv']);

    // Applications
    Route::get('/applications', [AdminApplicationController::class, 'index']);
    Route::get('/applications/{application}', [AdminApplicationController::class, 'show']);
    Route::put('/applications/{application}/status', [AdminApplicationController::class, 'updateStatus']);
    Route::post('/applications/{application}/notes', [AdminApplicationController::class, 'addNote']);
    Route::get('/applications/{application}/notes', [AdminApplicationController::class, 'getNotes']);

    // Interviews
    Route::get('/interviews', [InterviewController::class, 'index']);
    Route::post('/interviews', [InterviewController::class, 'store']);
    Route::put('/interviews/{interview}', [InterviewController::class, 'update']);
    Route::delete('/interviews/{interview}', [InterviewController::class, 'destroy']);

    // Positions
    Route::get('/positions', [AdminPositionController::class, 'index']);
    Route::post('/positions', [AdminPositionController::class, 'store']);
    Route::put('/positions/{position}', [AdminPositionController::class, 'update']);
    Route::delete('/positions/{position}', [AdminPositionController::class, 'destroy']);

    // Stats
    Route::get('/stats', StatsController::class);
});
