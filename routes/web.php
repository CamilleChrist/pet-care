<?php

use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PetController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VaccinationRecordController;
use App\Http\Controllers\WeightRecordController;
use App\Models\VaccinationRecord;
use App\Models\WeightRecord;
use Illuminate\Support\Facades\Route;

// ****  HOME  **** //
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// ****  AUTH  **** //
Route::controller(AuthController::class)->middleware('guest')->group(function () {
    Route::view('/login', 'auth.login')->name('login');
    Route::view('/register', 'auth.register')->name('register');
    Route::view('/forgot-password', 'auth.forgot-password')->name('password.request');
    Route::get('/reset-password/{token}', function (string $token) {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => request('email'),
        ]);
    })->name('password.reset');

    Route::post('/login', 'login')->name('login.attempt');
    Route::post('/register', 'register')->name('register.store');
    Route::post('/forgot-password', 'forgotPassword')->name('password.email');
    Route::post('/reset-password', 'resetPassword')->name('password.update');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ****  DASHBOARD  **** //
Route::get('/dashboard', DashboardController::class)->name('dashboard')->middleware('auth');

// ****  PROFILE  **** //
Route::prefix('/profile')->controller(ProfileController::class)->middleware('auth')->group(function () {
    Route::get('/', 'edit')->name('profile.edit');
    Route::patch('/', 'update')->name('profile.update');
    Route::delete('/', 'destroy')->name('profile.destroy');

    Route::get('/password', 'editPassword')->name('profile.password.edit');
    Route::patch('/password', 'updatePassword')->name('profile.password.update');
});

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

Route::prefix('/pets/{pet}/weight-records')->controller(WeightRecordController::class)->middleware('auth')->group(function () {
    Route::get('/create', 'create')->name('pets.weight-records.create')->can('create', [WeightRecord::class, 'pet']);
    Route::post('/', 'store')->name('pets.weight-records.store')->can('create', [WeightRecord::class, 'pet']);
});

Route::delete('/weight-records/{weightRecord}', [WeightRecordController::class, 'destroy'])
    ->name('weight-records.destroy')
    ->middleware('auth')
    ->can('delete', 'weightRecord');

// ****  PETS - Vaccination Records  **** //
Route::prefix('/pets/{pet}/vaccination-records')->controller(VaccinationRecordController::class)->middleware('auth')->group(function () {
    Route::get('/', 'index')->name('pets.vaccination-records.index')->can('viewAny', [VaccinationRecord::class, 'pet']);
    Route::get('/create', 'create')->name('pets.vaccination-records.create')->can('create', [VaccinationRecord::class, 'pet']);
    Route::post('/', 'store')->name('pets.vaccination-records.store')->can('create', [VaccinationRecord::class, 'pet']);
});

Route::get('/vaccination-records/{vaccinationRecord}', [VaccinationRecordController::class, 'show'])
    ->name('vaccination-records.show')
    ->can('update', 'vaccinationRecord')
    ->middleware('auth');

Route::get('/vaccination-records/{vaccinationRecord}/edit', [VaccinationRecordController::class, 'edit'])
    ->name('vaccination-records.edit')
    ->can('update', 'vaccinationRecord')
    ->middleware('auth');

Route::patch('/vaccination-records/{vaccinationRecord}', [VaccinationRecordController::class, 'update'])
    ->name('vaccination-records.update')
    ->can('update', 'vaccinationRecord')
    ->middleware('auth');

Route::delete('/vaccination-records/{vaccinationRecord}', [VaccinationRecordController::class, 'destroy'])
    ->name('vaccination-records.destroy')
    ->middleware('auth')
    ->can('delete', 'vaccinationRecord');

// ****  ADMIN  **** //
Route::prefix('/admin-pet-care')->name('admin.')->middleware(['auth', 'can:admin'])->group(function () {
    Route::get('/', fn () => redirect()->route('admin.users.index'))->name('home');
    Route::resource('users', AdminUserController::class)->except('create', 'store');
});
