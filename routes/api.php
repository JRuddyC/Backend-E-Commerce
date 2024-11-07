<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\PersonController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::controller(AuthController::class)->prefix('auth')->group(function () {
    Route::post('/root', 'first');
    Route::post('/register', 'register');
    Route::post('/login', 'login');
    Route::post('/logout', 'logout')->middleware('auth:sanctum');
});

Route::controller(UserController::class)->prefix('user')->group(function () {
    Route::get('/show', 'show');
    Route::get('/find/{id}', 'find');
    Route::post('/assign', 'assignRole');
});

Route::controller(RoleController::class)->prefix('role')->group(function () {
    Route::get('show', 'show');
    Route::post('/create', 'create');
    Route::post('/update/{id}', 'update');
    Route::get('/find/{id}', 'find');
});

Route::controller(PermissionController::class)->prefix('permission')->group(function () {
    Route::get('/show', 'show');
    Route::post('/create', 'create');
    Route::post('/assing', 'assing');
    Route::post('/update/{id}', 'update');
    Route::get('/find/{id}', 'find');
});

Route::controller(PasswordController::class)->prefix('password')->group(function () {
    Route::post('/reset', 'reset')->name('password.reset');
    Route::post('/forgot', 'forgot')->name('password.forgot');
});

Route::controller(PersonController::class)->prefix('person')->group(function () {
    Route::post('/root', 'first');
    Route::get('/show', 'show');
    Route::post('/register', 'register');
    Route::post('/update/{id}', 'update');
    Route::get('/findByCi/{ci}', 'findByCi');
    Route::get('/find/{id}', 'find');
});
