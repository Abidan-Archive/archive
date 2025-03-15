<?php

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->controller(AdminController::class)->group(function () {
    Route::get('/', 'index')->name('admin.index');

    Route::get('/user', 'user')->name('admin.user');

    Route::get('/review', 'reviewIndex')->name('admin.review');
    Route::post('/review/{id}', 'reviewApprove')->name('admin.approve');
    Route::patch('/admin/review/update/{id}', 'updateReport')->name('admin.report.update');

    Route::post('/assume', 'assume')->name('admin.assume');
    Route::post('/query', 'query')->name('admin.query');
    Route::post('/ban', 'ban')->name('admin.ban');
    Route::post('/reset-password', 'resetPassword')->name('admin.reset-password');
    Route::post('/assign-role', 'assignRole')->name('admin.assign-role');

    Route::get('/source', 'source')->name('admin.source');
    // Route::get('/report', 'report')->name('admin.report');
});
