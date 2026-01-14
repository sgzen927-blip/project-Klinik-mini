<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\QueueController;
use App\Http\Controllers\AdminQueueController; // PERBAIKAN: Tanpa folder Admin
use App\Http\Controllers\HomeController;

Route::get('/', function () {
    return redirect()->route('login');
});

Auth::routes(); 

Route::middleware(['auth'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/queue/create', [QueueController::class, 'create'])->name('queue.create');
    Route::post('/queue', [QueueController::class, 'store'])->name('queue.store');
    Route::get('/history', [QueueController::class, 'index'])->name('queue.index');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminQueueController::class, 'dashboard'])->name('admin.dashboard');
    Route::post('/call-next/{doctorId}', [AdminQueueController::class, 'callNext'])->name('admin.call-next');
    Route::patch('/queue/{queue}/update-status', [AdminQueueController::class, 'updateStatus'])->name('admin.status-update');
});