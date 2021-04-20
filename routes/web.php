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
