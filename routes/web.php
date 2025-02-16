<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $token = $_COOKIE['auth_token'] ?? null;

    if ($token) {
        return redirect('/breweries/list');
    }
    return view('login');
});

Route::get('/breweries/list', function () {
    $token = $_COOKIE['auth_token'] ?? null;

    if ($token) {
        return view('api');
    }
    return redirect('/');
});
