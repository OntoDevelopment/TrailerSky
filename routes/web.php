<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers;

Route::get('/', [Controllers\HomeController::class, 'index'])->name('home');
Route::get('/movies', [Controllers\IndexController::class, 'movies'])->name('movies');
Route::get('/shows', [Controllers\IndexController::class, 'shows'])->name('shows');

Route::get('/video/{id}', [Controllers\VideoController::class, 'index'])->name('video');
Route::get('/search', [Controllers\SearchController::class, 'index'])->name('search');
Route::get('/hashtag/{hashtag}', [Controllers\IndexController::class, 'hashtag'])->name('hashtag');

Route::get('/about', [Controllers\PageController::class, 'about'])->name('about');
Route::get('/page/{view}', [Controllers\PageController::class, 'page'])->name('page');

Route::get('/subscriber/notify', [Controllers\SubscriberController::class, 'verify'])->name('subscriber.verify');
Route::post('/subscriber/notify', [Controllers\SubscriberController::class, 'notify'])->name('subscriber.notify');

Route::middleware([\Orchid\Platform\Http\Middleware\Access::class])->prefix('admin-x')->group(function () {
    Route::get('/', [Controllers\Admin\DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/runplaceholder', [Controllers\Admin\DashboardController::class, 'runplaceholder'])->name('admin.runplaceholder');
    Route::get('/util/action/{action}', [Controllers\Admin\UtilController::class, 'action'])->name('admin.util.action');
    Route::get('/util/dismiss', [Controllers\Admin\UtilController::class, 'dismiss'])->name('admin.util.dismiss');
    Route::get('/util/set_tmdb_id', [Controllers\Admin\UtilController::class, 'set_tmdb_id'])->name('admin.util.set_tmdb_id');
    Route::get('/logs/notifications', [Controllers\Admin\LogsController::class, 'notifications'])->name('admin.logs.notifications');
});
