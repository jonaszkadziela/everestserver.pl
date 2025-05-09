<?php

use App\Http\Controllers\Admin\LinkedServiceController;
use App\Http\Controllers\Admin\PanelController;
use App\Http\Controllers\Admin\ServiceController as AdminServiceController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\UserSettingController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', fn () => view('home'))->name('home');
Route::get('/privacy', fn () => view('privacy'))->name('privacy');

Route::get('/language/{code}', [UserSettingController::class, 'language']);
Route::get('/theme/{mode}', [UserSettingController::class, 'theme']);

Route::middleware(['auth'])->group(function () {
    Route::get('/profile/disabled', [ProfileController::class, 'disabled'])->name('profile.disabled');
});

Route::middleware(['auth', 'enabled'])->group(function () {
    Route::middleware('admin')->prefix('admin')->group(function () {
        Route::get('/panel', [PanelController::class, 'index'])->name('admin.panel');

        Route::post('/linked-services/link', [LinkedServiceController::class, 'link'])->name('linked-services.link');
        Route::post('/linked-services/unlink', [LinkedServiceController::class, 'unlink'])->name('linked-services.unlink');

        Route::post('/services', [AdminServiceController::class, 'store'])->name('services.store');
        Route::patch('/services/{service}', [AdminServiceController::class, 'update'])->name('services.update');
        Route::delete('/services/{service}', [AdminServiceController::class, 'destroy'])->name('services.destroy');

        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::patch('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    });

    Route::middleware('verified')->group(function () {
        Route::get('/dashboard', fn () => view('dashboard'))->name('dashboard');
        Route::post('/services/link', [ServiceController::class, 'link'])->name('services.link');
    });

    Route::middleware('password.confirm')->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });
});

require __DIR__ . '/auth.php';
