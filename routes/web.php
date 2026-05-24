<?php
// FILE: routes/web.php

use App\Http\Controllers\EventController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('events.index');
});

// ─────────────────────────────────────────────────────────────
// PUBLIC routes (no auth needed)
// ─────────────────────────────────────────────────────────────
Route::get('/events',         [EventController::class, 'index'])->name('events.index');
Route::get('/events/archive', [EventController::class, 'archive'])->name('events.archive');

// ─────────────────────────────────────────────────────────────
// AUTH-ONLY static-path routes — MUST all come BEFORE {event}
// wildcard so Laravel doesn't swallow them as slugs.
// ─────────────────────────────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/dashboard',   [EventController::class, 'dashboard'])->name('dashboard');
    Route::get('/my-events',   [EventController::class, 'myEvents'])->name('events.my-events');

    // Profile (Breeze)
    Route::get('/profile',    [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile',  [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Create (static path — MUST be before {event})
    Route::get('/events/create', [EventController::class, 'create'])->name('events.create');
    Route::post('/events',       [EventController::class, 'store'])->name('events.store');
});

// ─────────────────────────────────────────────────────────────
// {event} wildcard routes — AFTER all static paths
// ─────────────────────────────────────────────────────────────
Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');

Route::middleware('auth')->group(function () {
    Route::get('/events/{event}/edit',    [EventController::class, 'edit'])->name('events.edit');
    Route::put('/events/{event}',         [EventController::class, 'update'])->name('events.update');
    Route::delete('/events/{event}',      [EventController::class, 'destroy'])->name('events.destroy');

    // Approval (admin only)
    Route::post('/events/{event}/approve', [EventController::class, 'approve'])->name('events.approve');
    Route::post('/events/{event}/reject',  [EventController::class, 'reject'])->name('events.reject');

    // Relaunch archived event (admin only)
    Route::post('/events/{event}/relaunch', [EventController::class, 'relaunch'])->name('events.relaunch');

    // Registration
    Route::post('/events/{event}/register',      [EventController::class, 'register'])->name('events.register');
    Route::delete('/events/{event}/unregister',  [EventController::class, 'unregister'])->name('events.unregister');
});

require __DIR__ . '/auth.php';
