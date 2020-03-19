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
// Route::get('/', 'PagesController@home');
// Route::get('/a-propos', 'PagesController@about');
// Route::get('/confidentialite', 'PagesController@confidentiality');
// Route::get('/centre-d-aide', 'PagesController@help');
// Route::get('/regles-de-securite', 'PagesController@securityRules');

// SPA VUE
Route::get('/{any}', 'PagesController@spa')->where('any', '.*');
