<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PetController;
use Illuminate\Support\Facades\Route;

// ****  HOME  **** //
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// ****  AUTH  **** //
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->name('password.request');

Route::get('/reset-password/{token}', function (string $token) {
    return view('auth.reset-password', [
        'token' => $token,
        'email' => request('email'),
    ]);
})->name('password.reset');

Route::controller(AuthController::class)->group(function () {
    Route::post('/login', 'login')->name('login.attempt');
    Route::post('/register', 'register')->name('register.store');
    Route::post('/logout', 'logout')->name('logout');
    Route::post('/forgot-password', 'forgotPassword')->name('password.email');
    Route::post('/reset-password', 'resetPassword')->name('password.update');
});

// ****  DASHBOARD  **** //
Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard')->middleware('auth');

// ****  PETS  **** //
Route::prefix('/pets')->controller(PetController::class)->middleware('auth')->group(function () {
    Route::get('/', 'index')->name('pets.index');
    Route::get('/create', 'create')->name('pets.create');
    Route::post('/', 'store')->name('pets.store');
    Route::get('/{pet}', 'show')->name('pets.show')->can('view', 'pet');
    Route::get('/{pet}/edit', 'edit')->name('pets.edit')->can('update', 'pet');
    Route::patch('/{pet}', 'update')->name('pets.update')->can('update', 'pet');
    Route::delete('/{pet}', 'destroy')->name('pets.destroy')->can('delete', 'pet');
});
