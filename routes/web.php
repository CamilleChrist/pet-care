<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::controller(AuthController::class)->group(function () {
    Route::post('/login', 'login')->name('login.attempt');
    Route::post('/register', 'register')->name('register.store');
    Route::post('/logout', 'logout')->name('logout');
});
