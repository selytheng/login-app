<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('login');
});
Route::get('/signin', function () {
    return view('login');
});
Route::get('/loginsuccess', function () {
    return view('loginsuccess');
});

