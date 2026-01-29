<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/signup', function () {
    return view('auth.signup');
})->name('signup');

Route::get('/signin', function () {
    return view('auth.signin');
})->name('signin'); 
