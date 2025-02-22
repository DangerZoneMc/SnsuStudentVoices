<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ChirpController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\AnnouncementController;

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

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('chirps', ChirpController::class)
        ->only(['index', 'store', 'update', 'destroy']);

    Route::get('/chirps/ceit', [ChirpController::class, 'ceit'])->name('chirps.ceit');
    Route::get('/chirps/cas', [ChirpController::class, 'cas'])->name('chirps.cas');
    Route::get('/chirps/cte', [ChirpController::class, 'cte'])->name('chirps.cte');
    Route::get('/chirps/cot', [ChirpController::class, 'cot'])->name('chirps.cot');

    Route::post('/chirps/{chirp}/like', [LikeController::class, 'store'])
        ->name('chirps.like');
        
    Route::post('/chirps/{chirp}/comments', [CommentController::class, 'store'])
        ->name('chirps.comments.store');
    Route::patch('/chirps/{chirp}/comments/{comment}', [CommentController::class, 'update'])
        ->name('chirps.comments.update');
    Route::delete('/chirps/{chirp}/comments/{comment}', [CommentController::class, 'destroy'])
        ->name('chirps.comments.destroy');

    Route::get('/organizations', [OrganizationController::class, 'index'])
        ->name('organizations');

    Route::post('/announcements', [AnnouncementController::class, 'store'])
        ->name('announcements.store')
        ->middleware('can:create,App\Models\Announcement');
});

require __DIR__.'/auth.php';
