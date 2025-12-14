<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CommentController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/settings.php';

Route::middleware(['auth', 'verified'])
    ->prefix('admin')
    ->name('admin.')
    ->group( function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        Route::patch('users/{user}/restore', [UserController::class, 'restore'])->name('users.restore');
        Route::resource('users', UserController::class);

        Route::resource('roles', RoleController::class)->only(['index']);
        Route::resource('permissions', PermissionController::class)->only(['index']);

        Route::resource('posts', PostController::class)->only(['index']);
        Route::resource('categories', CategoryController::class)->only(['index']);
        Route::resource('tags', TagController::class)->only(['index']);
        Route::resource('comments', CommentController::class)->only(['index']);
    });
