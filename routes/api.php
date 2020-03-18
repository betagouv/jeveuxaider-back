<?php


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// AUTH
Route::post('register/volontaire', 'Api\PassportController@registerVolontaire');
Route::post('register/responsable', 'Api\PassportController@registerResponsable');
Route::post('password/forgot', 'Api\PassportController@forgotPassword');
Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');

Route::get('releases', 'Api\ReleaseController@index');

Route::group(['middleware' => ['auth:api']], function () {
    // CONFIG
    Route::get('bootstrap', 'Api\ConfigController@bootstrap');
    Route::get('user', 'Api\UserController@show');

    Route::post('profile/{profile}', 'Api\ProfileController@update');
    Route::post('structure', 'Api\StructureController@store');
    Route::post('structure/{structure}', 'Api\StructureController@update');

    // AUTH
    Route::post('logout', 'Api\PassportController@logout');
});

Route::group(['middleware' => ['auth:api', 'has.context.role.header' ]], function () {
    // USERS
    Route::post('user', 'Api\UserController@update');
    Route::post('user/password', 'Api\UserController@updatePassword');

    // STRUCTURES
    Route::get('structures', 'Api\StructureController@index');
    Route::get('structure/{structure}', 'Api\StructureController@show');
    Route::get('structure/{structure}/missions', 'Api\StructureController@missions');

    Route::delete('structure/{structure}', 'Api\StructureController@delete');

    // STRUCTURE MEMBERS
    Route::post('structure/{structure}/missions', 'Api\StructureController@addMission');
    Route::get('structure/{structure}/members', 'Api\StructureController@members');
    Route::post('structure/{structure}/members', 'Api\StructureController@addMember');
    Route::delete('structure/{structure}/members/{member}', 'Api\StructureController@deleteMember');

    // MISSIONS
    Route::get('missions', 'Api\MissionController@index');
    Route::get('mission/{mission}', 'Api\MissionController@show');
    Route::post('mission/{mission}', 'Api\MissionController@update');
    Route::post('mission/{mission}/clone', 'Api\MissionController@clone');
    Route::delete('mission/{mission}', 'Api\MissionController@delete');

    // PROFILES
    Route::get('profiles', 'Api\ProfileController@index');
    Route::get('profile/{profile?}', 'Api\ProfileController@show');

    // EXPORT
    Route::get('structures/export', 'Api\StructureController@export');
    Route::get('missions/export', 'Api\MissionController@export');
    Route::get('profiles/export', 'Api\ProfileController@export');

    // STATISTICS
    Route::get('statistics/missions', 'Api\StatisticsController@missions');
    Route::get('statistics/structures', 'Api\StatisticsController@structures');
    Route::get('statistics/profiles', 'Api\StatisticsController@profiles');
});

// ONLY ADMIN
Route::group(['middleware' => ['auth:api', 'is.admin']], function () {
    // PROFILES
    Route::post('profile', 'Api\ProfileController@store');

    // TRASH
    // Route::get('trash', 'Api\TrashController@index');
    // Route::delete('structure/{id}/destroy', 'Api\StructureController@destroy');
    // Route::delete('young/{id}/destroy', 'Api\YoungController@destroy');
    // Route::delete('mission/{id}/destroy', 'Api\MissionController@destroy');

    // RELEASES
    // Route::get('release/{release}', 'Api\ReleaseController@show');
    // Route::post('release/{release}', 'Api\ReleaseController@update');
    // Route::delete('release/{release}', 'Api\ReleaseController@delete');

    // IMPERSONNATE
    // Route::post('impersonate/{user}', 'Api\UserController@impersonate');
    // Route::delete('impersonate/{token}', 'Api\UserController@stopImpersonate');
});

// Endpoints utilisés par des applications externes
// TODO : Validation par IP de Diagoriente
Route::prefix('v1')->group(function () {
    Route::get('connect/{token}', 'Api\SnuConnectController@show');
    Route::get('generate-token/{first_name}/{last_name}/{email}/{id}', 'Api\SnuConnectController@generateUserToken');
});
