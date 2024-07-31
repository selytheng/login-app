<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/signin', function () {
    return view('login');
});
Route::get('/loginsucsess', function () {
    return view('loginsucsess');
});

