<?php

use App\Http\Controllers\Api\BreweriesController;
use App\Http\Controllers\Api\LoginController;
use Illuminate\Support\Facades\Route;


Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('/breweries', [BreweriesController::class, 'index']);
});
