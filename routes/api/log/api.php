<?php

use Illuminate\Support\Facades\Route;

Route::post('logs', [\App\Http\Controllers\LogController::class, 'store']);
