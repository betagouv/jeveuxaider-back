<?php

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

Route::get('/', function () {
    return view('pages.home');
});

Route::get('/a-propos', function () {
    return view('pages.about');
});

Route::get('/confidentialite', function () {
    return view('pages.confidentiality');
});

Route::get('/centre-d-aide', function () {
    return view('pages.help');
});

Route::get('/regles-de-securite', function () {
    return view('pages.security-rules');
});

Route::get('/{any}', function () {
    return view('layouts.app');
})->where('any', '.*');
