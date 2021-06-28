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

use App\Models\User;

Route::get('api/api-engagement/flux', 'Api\EngagementController@feed');

Route::get('test', function() {

    $user = User::find(2);

    $user->context_role = 'responsable';
    $user->contextable_id = 3;
    $user->contextable_type = 'territoire';

    $user->save();

    return $user;
});
