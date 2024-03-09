<?php

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

// Auth Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [App\Http\Controllers\Auth\AuthController::class, 'index_login'])->name('login');
    Route::post('/login', [App\Http\Controllers\Auth\AuthController::class, 'login']);
});

Route::get('/register', [App\Http\Controllers\Auth\AuthController::class, 'index_register'])->name('register');
Route::post('/register', [App\Http\Controllers\Auth\AuthController::class, 'register']);

Route::middleware('auth')->group(function () {
    Route::post('/logout', [App\Http\Controllers\Auth\AuthController::class, 'logout'])->name('logout');

    // Home and Resource Routes
    Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    // Home Routes
    Route::get('/home/profile/{id}', [App\Http\Controllers\HomeController::class, 'profile'])->name('home.profile');
    Route::post('home/addTestCase', [App\Http\Controllers\HomeController::class, 'addTestCase'])->name('home.addTestCase');
    Route::put('home/editTestCase/{id}', [App\Http\Controllers\HomeController::class, 'editTestCase'])->name('home.editTestCase');
    Route::delete('home/deleteTestCase/{id}', [App\Http\Controllers\HomeController::class, 'deleteTestCase'])->name('home.deleteTestCase');
    Route::post('home/saveSelectedTestCases', [App\Http\Controllers\HomeController::class, 'saveSelectedTestCases'])->name('home.saveSelectedTestCases');
    Route::get('home/showSelectedTestCases', [App\Http\Controllers\HomeController::class, 'showSelectedTestCases'])->name('home.showSelectedTestCases');
    Route::delete('home/deleteAllSelectedTestCases', [App\Http\Controllers\HomeController::class, 'deleteAllSelectedTestCases'])->name('home.deleteAllSelectedTestCases');
    Route::delete('home/deleteSelectedTestCase/{id}', [App\Http\Controllers\HomeController::class, 'deleteSelectedTestCase'])->name('home.deleteSelectedTestCase');
    Route::get('home/showUser', [App\Http\Controllers\HomeController::class, 'showUser'])->name('home.showUser');
    Route::delete('home/deleteUser/{id}', [App\Http\Controllers\HomeController::class, 'deleteUser'])->name('home.deleteUser');
    Route::post('home/export', [App\Http\Controllers\HomeController::class, 'export'])->name('home.export');

});