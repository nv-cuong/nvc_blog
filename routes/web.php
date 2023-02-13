<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Author\DashboardController as AuthorDashboardController;
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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group([
    'prefix' => 'admin', 'namespace' => 'admin',
    'middleware' => ['auth', 'admin'], 'as' => 'admin.'
], function () {
    Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

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
});

Route::group([
    'prefix' => 'author', 'namespace' => 'author',
    'middleware' => ['auth', 'author'], 'as' => 'author.'
], function () {
    Route::get('dashboard', [AuthorDashboardController::class, 'index'])->name('dashboard');
});
