<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostShowController;
use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');
Route::get('/category/{type}', [CategoryController::class, 'show'])->name('category.show');
Route::get('/post/{slug}', PostShowController::class)->name('posts.show');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminLoginController::class, 'create'])->name('login')->middleware('guest');
    Route::post('/login', [AdminLoginController::class, 'store'])->middleware('guest');
    Route::post('/logout', [AdminLoginController::class, 'destroy'])->name('logout')->middleware('auth');

    Route::middleware('auth')->group(function () {
        Route::get('/', DashboardController::class)->name('dashboard');
        Route::resource('posts', PostController::class)->except(['show']);
    });
});
