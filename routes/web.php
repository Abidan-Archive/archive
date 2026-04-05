<?php

use App\Http\Controllers\EventController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SourceController;
use App\Http\Controllers\StubController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\UserController;

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
Route::get('about', [HomeController::class, 'about'])->name('about');
Route::get('search', [SearchController::class, 'search'])->name('search');

Route::resource('report', ReportController::class);

Route::resource('event', EventController::class);
Route::apiResource('event.source', SourceController::class)->except(['index']); // No html routes

Route::get('stub', [StubController::class, 'index'])->name('stub.index');
Route::get('event/{event}/source/{source}/scrub', [StubController::class, 'create'])->name('event.source.scrub');
Route::resource('event.source.stub', StubController::class)->only(['store', 'destroy']);
Route::get('event/{event}/source/{source}/stub/{stub}/transcribe', [ReportController::class, 'createFromStub'])->name('stub.transcribe');

Route::resource('tag', TagController::class)->scoped(['tag' => 'name']);

Route::resource('user', UserController::class)->only(['show', 'edit', 'update', 'destroy'])->scoped(['user' => 'username']);
Route::post('like', [LikeController::class, 'like'])->name('like');
Route::delete('like', [LikeController::class, 'unlike'])->name('unlike');

// Redirects for legacy
Route::get('events/{event}', [HomeController::class, 'redirect']);
Route::get('redirect', [HomeController::class, 'handleRedirect'])->name('handleRedirect');

require __DIR__.'/admin.php';
require __DIR__.'/auth.php';
require __DIR__.'/oauth.php';
