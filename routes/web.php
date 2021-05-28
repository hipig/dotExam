<?php

use App\Http\Controllers;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [Controllers\HomeController::class, 'index'])->name('home');

Route::get('subjects', [Controllers\SubjectsController::class, 'index'])->name('subjects.index');

Route::get('subjects/{parentSubject}/{subject?}/{paperType?}', [Controllers\SubjectsController::class, 'show'])->name('subjects.show');

Route::get('papers/{paper}/get-totalities', [Controllers\PapersController::class, 'getTotalities'])->name('papers.getTotalities');

Route::middleware('guest')->group(function () {

    Route::get('login', [Controllers\Auth\AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [Controllers\Auth\AuthenticatedSessionController::class, 'store']);

    Route::get('register', [Controllers\Auth\RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [Controllers\Auth\RegisteredUserController::class, 'store']);

    Route::get('forgot-password', [Controllers\Auth\PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('forgot-password', [Controllers\Auth\PasswordResetLinkController::class, 'store'])->name('password.email');

    Route::get('/reset-password/{token}', [Controllers\Auth\NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('/reset-password', [Controllers\Auth\NewPasswordController::class, 'store'])->name('password.update');

});

Route::middleware('auth')->group(function () {
    // Auth
    Route::get('/verify-email', [Controllers\Auth\EmailVerificationPromptController::class, '__invoke'])->name('verification.notice');
    Route::get('/verify-email/{id}/{hash}', [Controllers\Auth\VerifyEmailController::class, '__invoke'])->middleware(['signed', 'throttle:6,1'])->name('verification.verify');

    Route::post('/email/verification-notification', [Controllers\Auth\EmailVerificationNotificationController::class, 'store'])->middleware(['throttle:6,1'])->name('verification.send');

    Route::get('/confirm-password', [Controllers\Auth\ConfirmablePasswordController::class, 'show'])->name('password.confirm');
    Route::post('/confirm-password', [Controllers\Auth\ConfirmablePasswordController::class, 'store']);

    Route::post('/logout', [Controllers\Auth\AuthenticatedSessionController::class, 'destroy'])->name('logout');

    Route::post('papers/{paper}/records', [Controllers\PaperRecordsController::class, 'store'])->name('paperRecords.store');

    Route::get('records/{record}/start', [Controllers\PaperRecordsController::class, 'show'])->name('paperRecords.show');

    Route::get('records/{record}/result', [Controllers\PaperRecordsController::class, 'result'])->name('paperRecords.showResult');

    Route::post('records/{record}/{paperItem}', [Controllers\PaperRecordItemsController::class, 'store'])->name('paperRecords.items.store');

    Route::post('records/{record}', [Controllers\PaperRecordItemsController::class, 'batchStore'])->name('paperRecords.items.batchStore');

});
