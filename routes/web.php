<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers;

Route::get('/', function () {
    return view('welcome');
});

Route::group(['prefix' => 'admin', 'auth:sanctum'], function () {
    Route::get('/', [Controllers\Admin\DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/runplaceholder', [Controllers\Admin\DashboardController::class, 'runplaceholder'])->name('admin.runplaceholder');
    Route::get('/util/action/{action}', [Controllers\Admin\UtilController::class, 'action'])->name('admin.util.action');
    Route::get('/util/dismiss', [Controllers\Admin\UtilController::class, 'dismiss'])->name('admin.util.dismiss');
    Route::get('/util/set_tmdb_id', [Controllers\Admin\UtilController::class, 'set_tmdb_id'])->name('admin.util.set_tmdb_id');
    
});
