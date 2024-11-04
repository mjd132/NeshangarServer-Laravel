<?php

use App\Http\Controllers\UserEventController;
use Illuminate\Support\Facades\Route;

Route::get('/user/create', [UserEventController::class, 'create'])->name('user.create');

// Route to store the event
Route::post('/user/update', [UserEventController::class, 'update'])->name('user.update');

// Route to display the events
Route::get('/user', [UserEventController::class, 'get'])->name('user.index');
