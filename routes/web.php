<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\PostController as AdminPostController;
use App\Http\Controllers\Admin\SubscriberController as AdminSubscriberController;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Author\DashboardController as AuthorDashboardController;
use App\Http\Controllers\Author\PostController as AuthorPostController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SubscriberController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [HomeController::class, 'index']);

Auth::routes();

Route::post('subscriber', [SubscriberController::class, 'subscriber'])
    ->name('subscriber');

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group([
    'prefix' => 'admin', 'namespace' => 'Admin',
    'middleware' => ['auth', 'admin'], 'as' => 'admin.'
], function () {

    Route::get('dashboard', [AdminDashboardController::class, 'index'])
        ->name('dashboard');

    Route::prefix('/tag')->name('tag.')->group(function () {
        Route::get('/', [TagController::class, 'index'])
            ->name('index');
        Route::get('/create', [TagController::class, 'create'])
            ->name('create');
        Route::post('/store', [TagController::class, 'store'])
            ->name('store');
        Route::get('/edit/{id}', [TagController::class, 'edit'])
            ->name('edit');
        Route::put('/update/{id}', [TagController::class, 'update'])
            ->name('update');
        Route::get('/show/{id}', [TagController::class, 'show'])
            ->name('show');
        Route::delete('/delete/{id}', [TagController::class, 'delete'])
            ->name('delete');
    });

    Route::prefix('/category')->name('category.')->group(function () {
        Route::get('/', [CategoryController::class, 'index'])
            ->name('index');
        Route::get('/create', [CategoryController::class, 'create'])
            ->name('create');
        Route::post('/store', [CategoryController::class, 'store'])
            ->name('store');
        Route::get('/edit/{id}', [CategoryController::class, 'edit'])
            ->name('edit');
        Route::put('/update/{id}', [CategoryController::class, 'update'])
            ->name('update');
        Route::get('/show/{id}', [CategoryController::class, 'show'])
            ->name('show');
        Route::delete('/delete/{id}', [CategoryController::class, 'delete'])
            ->name('delete');
    });

    Route::prefix('/post')->name('post.')->group(function () {
        Route::get('/', [AdminPostController::class, 'index'])
            ->name('index');
        Route::get('/create', [AdminPostController::class, 'create'])
            ->name('create');
        Route::post('/store', [AdminPostController::class, 'store'])
            ->name('store');
        Route::get('/edit/{id}', [AdminPostController::class, 'edit'])
            ->name('edit');
        Route::put('/update/{id}', [AdminPostController::class, 'update'])
            ->name('update');
        Route::get('/show/{id}', [AdminPostController::class, 'show'])
            ->name('show');
        Route::delete('/delete/{id}', [AdminPostController::class, 'delete'])
            ->name('delete');
        Route::put('/approve/{id}', [AdminPostController::class, 'approval'])
            ->name('approve');
        Route::get('/pending', [AdminPostController::class, 'pending'])
            ->name('pending');
    });

    Route::prefix('/subscriber')->name('subscriber.')->group(function () {
        Route::get('/', [AdminSubscriberController::class, 'index'])
            ->name('index');
        Route::delete('/delete/{id}', [AdminSubscriberController::class, 'delete'])
            ->name('delete');
    });
});

Route::group([
    'prefix' => 'author', 'namespace' => 'Author',
    'middleware' => ['auth', 'author'], 'as' => 'author.'
], function () {
    Route::get('dashboard', [AuthorDashboardController::class, 'index'])->name('dashboard');

    Route::prefix('/post')->name('post.')->group(function () {
        Route::get('/', [AuthorPostController::class, 'index'])
            ->name('index');
        Route::get('/create', [AuthorPostController::class, 'create'])
            ->name('create');
        Route::post('/store', [AuthorPostController::class, 'store'])
            ->name('store');
        Route::get('/edit/{id}', [AuthorPostController::class, 'edit'])
            ->name('edit');
        Route::put('/update/{id}', [AuthorPostController::class, 'update'])
            ->name('update');
        Route::get('/show/{id}', [AuthorPostController::class, 'show'])
            ->name('show');
        Route::delete('/delete/{id}', [AuthorPostController::class, 'delete'])
            ->name('delete');
        Route::delete('/approve/{id}', [AuthorPostController::class, 'approve'])
            ->name('approve');
    });
});
