<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CommentController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\CommentController as ControllersCommentController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

/*
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');
*/

ROute::get('/', [HomeController::class, 'index'])->name('blog.index');
ROute::get('posts/{slug}', [HomeController::class, 'show'])->name('blog.show');
Route::get('categories/{slug}', [HomeController::class, 'category'])->name('blog.category');
Route::get('tags/{slug}', [HomeController::class, 'tag'])->name('blog.tag');

Route::post('/posts/{slug}/comments', [ControllersCommentController::class, 'store'])
    ->middleware(['auth'])
    ->name('blog.comments.store');

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

        Route::resource('roles', RoleController::class)->only(['index', 'edit', 'update']);
        Route::resource('permissions', PermissionController::class)->only(['index']);

        Route::patch('posts/{post}/restore', [PostController::class, 'restore'])->name('posts.restore');
        Route::resource('posts', PostController::class);

        Route::patch('categories/{category}/restore', [CategoryController::class, 'restore'])->name('categories.restore');
        Route::resource('categories', CategoryController::class);
        
        Route::patch('tags/{tag}/restore', [TagController::class, 'restore'])->name('tags.restore');
        Route::resource('tags', TagController::class);

        Route::resource('comments', CommentController::class)->only(['index']);
    });
