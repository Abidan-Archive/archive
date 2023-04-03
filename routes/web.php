<?php

use App\Http\Controllers\EventController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\TagController;

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

Route::get('/', [HomeController::class, 'home'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/search', [SearchController::class, 'search'])->name('search');

// Redirects for legacy
Route::get('/events/{event}', [HomeController::class, 'redirect']);
Route::get('/redirect', [HomeController::class, 'handleRedirect'])->name('handleRedirect');

Route::resource('event', EventController::class);
Route::resource('event.report', ReportController::class)->shallow();
Route::resource('tag', TagController::class);

Route::post('like', [LikeController::class, 'like'])->name('like');
Route::delete('like', [LikeController::class, 'unlike'])->name('unlike');

require __DIR__.'/auth.php';
