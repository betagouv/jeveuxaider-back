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

Route::get('faqs', 'Api\FaqController@index');
Route::get('page/{page}', 'Api\PageController@show');

Route::get('mission/{mission}', 'Api\MissionController@show');
Route::get('structure/{structure}/availableMissions', 'Api\StructureController@availableMissions');

Route::get('bootstrap', 'Api\ConfigController@bootstrap');

Route::get('collectivity/{slugOrId}', 'Api\CollectivityController@show');

Route::group(['middleware' => ['auth:api']], function () {
    // CONFIG
    Route::get('user', 'Api\UserController@show');
    Route::post('user/anonymize', 'Api\UserController@anonymize');

    Route::post('profile/{profile}', 'Api\ProfileController@update');
    Route::get('profile/{profile}/participations', 'Api\ProfileController@participations');

    Route::post('structure', 'Api\StructureController@store');
    Route::post('structure/{structure}', 'Api\StructureController@update');

    Route::post('participation', 'Api\ParticipationController@store');
    Route::post('participation/{participation}/cancel', 'Api\ParticipationController@cancel');

    Route::post('user/password', 'Api\UserController@updatePassword');

    // AUTH
    Route::post('logout', 'Api\PassportController@logout');
});

// Pour info : Les middleware 'auth:api', 'has.context.role.header' ajoutent 9 queries
Route::group(['middleware' => ['auth:api', 'has.context.role.header' ]], function () {
    // USERS
    Route::post('user', 'Api\UserController@update');

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

    // RELEASES
    Route::get('releases', 'Api\ReleaseController@index');

    // STATISTICS
    Route::get('statistics/missions', 'Api\StatisticsController@missions');
    Route::get('statistics/departments', 'Api\StatisticsController@departments');
    Route::get('statistics/structures', 'Api\StatisticsController@structures');
    Route::get('statistics/profiles', 'Api\StatisticsController@profiles');
    Route::get('statistics/participations', 'Api\StatisticsController@participations');
    Route::get('charts/created', 'Api\ChartController@created');

    // REMINDERS
    Route::get('reminders', 'Api\ConfigController@reminders');

    // DOCUMENTS
    Route::get('documents', 'Api\DocumentController@index');
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
    Route::post('collectivity', 'Api\CollectivityController@store');
    Route::post('collectivity/{collectivity}', 'Api\CollectivityController@update');
    Route::post('collectivity/{collectivity}/upload', 'Api\CollectivityController@upload');
    Route::delete('collectivity/{collectivity}/upload', 'Api\CollectivityController@uploadDelete');
    Route::delete('collectivity/{collectivity}', 'Api\CollectivityController@delete');

    // THEMATIQUES
    Route::get('thematiques', 'Api\ThematiqueController@index');
    Route::post('thematique', 'Api\ThematiqueController@store');
    Route::post('thematique/{thematique}', 'Api\ThematiqueController@update');
    Route::post('thematique/{thematique}/upload', 'Api\ThematiqueController@upload');
    Route::delete('thematique/{thematique}/upload', 'Api\ThematiqueController@uploadDelete');
    Route::delete('thematique/{thematique}', 'Api\ThematiqueController@delete');

    // MISSION TEMPLATES
    Route::get('mission-templates', 'Api\MissionTemplateController@index');
    Route::post('mission-template', 'Api\MissionTemplateController@store');
    Route::post('mission-template/{missionTemplate}', 'Api\MissionTemplateController@update');
    Route::delete('mission-template/{missionTemplate}', 'Api\MissionTemplateController@delete');

    // DOCUMENTS
    Route::get('document/{document}', 'Api\DocumentController@show');
    Route::post('document', 'Api\DocumentController@store');
    Route::post('document/{document}', 'Api\DocumentController@update');
    Route::post('document/{document}/upload', 'Api\DocumentController@upload');
    Route::delete('document/{document}/upload', 'Api\DocumentController@uploadDelete');
    Route::delete('document/{document}', 'Api\DocumentController@delete');

    // FAQ
    Route::post('faq', 'Api\FaqController@store');
    Route::post('faq/{faq}', 'Api\FaqController@update');
    Route::get('faq/{faq}', 'Api\FaqController@show');
    Route::delete('faq/{faq}', 'Api\FaqController@delete');

    // RELEASES
    Route::post('release', 'Api\ReleaseController@store');
    Route::post('release/{release}', 'Api\ReleaseController@update');
    Route::get('release/{release}', 'Api\ReleaseController@show');
    Route::delete('release/{release}', 'Api\ReleaseController@delete');

    // PAGES
    Route::get('pages', 'Api\PageController@index');
    Route::post('page', 'Api\PageController@store');
    Route::post('page/{page}', 'Api\PageController@update');
    Route::delete('page/{page}', 'Api\PageController@delete');

    // IMPERSONNATE
    Route::post('impersonate/{user}', 'Api\UserController@impersonate');
    Route::delete('impersonate/{token}', 'Api\UserController@stopImpersonate');

    // TABLE EXPORT
    Route::post('{table}/export/table', 'Api\ConfigController@export');
});
