<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return 'Welcome to Neshangar\'s server';
});

Route::get('/show-logs', function () {

    $logFile = storage_path('logs/laravel.log');

    if (file_exists($logFile)) {

        $logs = file_get_contents($logFile);

        return '<pre>' . $logs . '</pre>';
    } else {
        return 'No log file found.';
    }
});
