<?php

use App\Http\Controllers\EventController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SourceController;
use App\Http\Controllers\StubController;
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

// Route::get('/testaudio', fn() => response()->file('storage/sources/3_3_YZTShKdDHOrptCtEZmPplZASRMYYk4gRBqBnSAME.m4a'));
Route::get('/testaudio', fn() => response()->file('storage/sources/Bloodline_Release_Part_1.mp3'));

// Redirects for legacy
Route::get('/events/{event}', [HomeController::class, 'redirect']);
Route::get('/redirect', [HomeController::class, 'handleRedirect'])->name('handleRedirect');

Route::resource('event', EventController::class);
Route::resource('event.report', ReportController::class)->shallow();
Route::resource('event.source.stub', StubController::class);
Route::get('/event/{event}/source/{source}', [SourceController::class, 'show'])->name('event.source.show');
Route::delete('/event/{event}/source/{source}', [SourceController::class, 'destroy'])->name('event.source.destroy');
Route::get('/stubs', [StubController::class, 'all'])->name('stubs.all');

Route::resource('tag', TagController::class);

Route::post('like', [LikeController::class, 'like'])->name('like');
Route::delete('like', [LikeController::class, 'unlike'])->name('unlike');

require __DIR__.'/admin.php';
require __DIR__.'/auth.php';
