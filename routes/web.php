<?php

use App\Http\Controllers\FileExporterController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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

Route::get('/dashboard', [FileExporterController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    Route::prefix('file-exporter')->name('file.exporter.')->group(function () {
        Route::get("create", [FileExporterController::class, 'create'])->name('create');
        Route::post("store", [FileExporterController::class, 'store'])->name('store');
        Route::get("edit/{id}", [FileExporterController::class, 'edit'])->name('edit');
        Route::post("update", [FileExporterController::class, 'update'])->name('update');
        Route::get("destroy/{id}", [FileExporterController::class, 'destroy'])->name('destroy');
        Route::get('export/{id}', [FileExporterController::class, 'export'])->name('export');
    });

});



require __DIR__ . '/auth.php';
