<?php

use App\Http\Controllers\LogController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return 'Welcome to Neshangar\'s server';
});

Route::get('/show-logs', [LogController::class, 'index'])->name('logs.index');

Route::get('/clear-logs', [LogController::class, 'clear'])->name('logs.clear');
