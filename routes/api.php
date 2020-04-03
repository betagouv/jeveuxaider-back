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
Route::post('register/invitation', 'Api\PassportController@registerInvitation');
Route::post('password/forgot', 'Api\PassportController@forgotPassword');
Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');

Route::get('releases', 'Api\ReleaseController@index');
Route::get('faqs', 'Api\FaqController@index');

Route::get('mission/{mission}', 'Api\MissionController@show');
Route::get('structure/{structure}/availableMissions', 'Api\StructureController@availableMissions');

Route::post('submit/collectivity', 'Api\CollectivityController@submit');
Route::get('collectivite/{slugOrId}', 'Api\CollectivityController@show');

Route::group(['middleware' => ['auth:api']], function () {
    // CONFIG
    Route::get('bootstrap', 'Api\ConfigController@bootstrap');
    Route::get('user', 'Api\UserController@show');

    Route::post('profile/{profile}', 'Api\ProfileController@update');
    Route::get('profile/{profile}/participations', 'Api\ProfileController@participations');

    Route::post('structure', 'Api\StructureController@store');
    Route::post('structure/{structure}', 'Api\StructureController@update');

    Route::post('participation', 'Api\ParticipationController@store');

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
    Route::post('mission/{mission}', 'Api\MissionController@update');
    Route::post('mission/{mission}/clone', 'Api\MissionController@clone');
    Route::delete('mission/{mission}', 'Api\MissionController@delete');

    // PROFILES
    Route::get('profiles', 'Api\ProfileController@index');
    Route::get('profile/{profile?}', 'Api\ProfileController@show');

    // PARTICIPATIONS
    Route::delete('participation/{participation}', 'Api\ParticipationController@delete');
    Route::get('participations', 'Api\ParticipationController@index');
    Route::post('participation/{participation}', 'Api\ParticipationController@update');
    Route::post('participations/mass-validation', 'Api\ParticipationController@massValidation');

    // EXPORT
    Route::get('structures/export', 'Api\StructureController@export');
    Route::get('missions/export', 'Api\MissionController@export');
    Route::get('profiles/export', 'Api\ProfileController@export');
    Route::get('participations/export', 'Api\ParticipationController@export');

    // STATISTICS
    Route::get('statistics/missions', 'Api\StatisticsController@missions');
    Route::get('statistics/analytics', 'Api\StatisticsController@analytics');
    Route::get('statistics/structures', 'Api\StatisticsController@structures');
    Route::get('statistics/profiles', 'Api\StatisticsController@profiles');
    Route::get('statistics/participations', 'Api\StatisticsController@participations');
});

// ONLY ADMIN
Route::group(['middleware' => ['auth:api', 'is.admin']], function () {
    // PROFILES
    Route::post('profile', 'Api\ProfileController@store');

    // TRASH
    Route::get('trash', 'Api\TrashController@index');
    Route::delete('structure/{id}/destroy', 'Api\StructureController@destroy');
    Route::delete('collectivity/{id}/destroy', 'Api\CollectivityController@destroy');
    Route::delete('mission/{id}/destroy', 'Api\MissionController@destroy');
    Route::delete('participation/{id}/destroy', 'Api\ParticipationController@destroy');

    // COLLECTIVITIES
    Route::get('collectivities', 'Api\CollectivityController@index');
    Route::get('collectivity/{slugOrId}', 'Api\CollectivityController@show');
    Route::post('collectivity', 'Api\CollectivityController@store');
    Route::post('collectivity/{collectivity}', 'Api\CollectivityController@update');
    Route::delete('collectivity/{collectivity}', 'Api\CollectivityController@delete');

    // FAQ
    Route::post('faq', 'Api\FaqController@store');
    Route::post('faq/{faq}', 'Api\FaqController@update');
    Route::get('faq/{faq}', 'Api\FaqController@show');

    // RELEASES
    // Route::get('release/{release}', 'Api\ReleaseController@show');
    // Route::post('release/{release}', 'Api\ReleaseController@update');
    // Route::delete('release/{release}', 'Api\ReleaseController@delete');

    // IMPERSONNATE
    Route::post('impersonate/{user}', 'Api\UserController@impersonate');
    Route::delete('impersonate/{token}', 'Api\UserController@stopImpersonate');
});
