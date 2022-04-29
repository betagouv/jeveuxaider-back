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

use Illuminate\Support\Facades\Route;

// AUTH
Route::post('register/volontaire', 'Api\PassportController@registerVolontaire');
Route::post('register/responsable', 'Api\PassportController@registerResponsable');
Route::post('password/forgot', 'Api\PassportController@forgotPassword');
Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');

Route::get('missions/prioritaires', 'Api\MissionController@prioritaires');
Route::get('missions/{mission}', 'Api\MissionController@show');
Route::get('missions/{mission}/similar', 'Api\MissionController@similar');
Route::get('associations/{slugOrId}', 'Api\StructureController@associationSlugOrId');

Route::get('territoires/{name}/exist', 'Api\TerritoireController@exist');
Route::get('structures/{rnaOrName}/exist', 'Api\StructureController@exist');
Route::get('structures/{structure}/available-missions', 'Api\StructureController@availableMissions');

Route::get('sitemap', 'Api\ConfigController@sitemap');

Route::get('domaines/{slugOrId}', 'Api\DomaineController@show');
Route::get('domaines/{slugOrId}/statistics', 'Api\DomaineController@statistics');

Route::get('activities/{slugOrId}', 'Api\ActivityController@show');

Route::post('sendinblue/contact', 'Api\SendInBlueController@store');

Route::get('invitations/{token}', 'Api\InvitationController@show');
Route::post('invitations/{token}/register', 'Api\InvitationController@register');

Route::post('firstname', 'Api\ProfileController@firstname');

Route::get('franceconnect/login-authorize', 'Auth\FranceConnectController@oauthLoginAuthorize');
Route::get('franceconnect/login-callback', 'Auth\FranceConnectController@oauthLoginCallback');

Route::get('territoires', 'Api\TerritoireController@index');
Route::get('territoires/{slugOrId}', 'Api\TerritoireController@show');
Route::get('territoires/{territoire}/available-cities', 'Api\TerritoireController@availableCities');

Route::post('reseaux/lead', 'Api\ReseauController@lead');
Route::get('reseaux/{reseau}', 'Api\ReseauController@show');
Route::get('reseaux/{reseau}/structures', 'Api\ReseauController@structures');

Route::get('notification-temoignage/{token}', 'Api\NotificationTemoignageController@show');
Route::get('participations/{participation}/temoignage', 'Api\ParticipationController@temoignage');
// Route::get('participation/{participation}/benevole-name', 'Api\ParticipationController@benevoleName');
// Route::get('participation/{participation}/mission', 'Api\ParticipationController@mission');
Route::post('temoignages', 'Api\TemoignageController@store');

Route::get('settings/messages', 'Api\SettingController@messages');
Route::get('settings/general', 'Api\SettingController@general');

Route::group(['middleware' => ['auth:api']], function () {

    Route::get('user', 'Api\UserController@me');
    Route::get('user/unread-messages', 'Api\UserController@unreadMessages');
    Route::get('user/participations', 'Api\UserController@participations');
    Route::post('user/anonymize', 'Api\UserController@anonymize');
    Route::put('user', 'Api\UserController@update');
    Route::get('profiles/{profile}', 'Api\ProfileController@show');
    Route::put('profiles/{profile}', 'Api\ProfileController@update');
    Route::get('user/actions', 'Api\ActionController@index');
    Route::get('user/actions/benevole', 'Api\ActionController@benevole');

    Route::get('medias', 'Api\MediaController@index');
    Route::post('medias/{modelType}/{modelId}/{collectionName}', 'Api\MediaController@store');
    Route::put('medias/{media}', 'Api\MediaController@update');
    Route::delete('medias/{media}', 'Api\MediaController@delete');

    Route::post('structure', 'Api\StructureController@store');
    Route::put('structures/{structure}', 'Api\StructureController@update');
    Route::post('structures/{structure}/unregister', 'Api\StructureController@unregister');
    Route::post('structures/{structure}/responsables', 'Api\StructureController@responsables');
    Route::post('structures/{structure}/waiting-participations', 'Api\StructureController@waitingParticipations');
    Route::post('structures/{structure}/validate-waiting-participations', 'Api\StructureController@validateWaitingParticipations');

    Route::post('participations', 'Api\ParticipationController@store');
    Route::put('participations/{participation}/cancel', 'Api\ParticipationController@cancel');

    Route::post('user/password', 'Api\UserController@updatePassword');

    // MESSAGES
    Route::get('conversations', 'Api\ConversationsController@index');
    Route::get('conversations/{conversation}', 'Api\ConversationsController@show')->where('conversation', '[0-9]+');
    Route::get('conversations/{conversation}/messages', 'Api\ConversationsController@messages');
    Route::post('conversations/{conversation}/messages', 'Api\MessagesController@store');
    Route::get('conversations/{conversation}/benevole', 'Api\ConversationsController@benevole');
    Route::post('conversations/{conversation}/setStatus', 'Api\ConversationsController@setStatus');

    Route::post('invitations/{token}/resend', 'Api\InvitationController@resend');
    Route::delete('invitations/{token}/delete', 'Api\InvitationController@delete');
    Route::post('invitations/{token}/accept', 'Api\InvitationController@accept');

    Route::post('logout', 'Api\PassportController@logout');

    // API ENGAGEMENT
    Route::get('apiengagement/mymission/{id}', 'Api\ApiEngagementController@myMission');

    Route::get('/vocabularies/{vocabulary:slug}/terms', 'Api\TermController@index');

    Route::delete('impersonate/{token}', 'Api\UserController@stopImpersonate');
});

// Pour info : Les middleware 'auth:api', 'has.context.role.header' ajoutent 9 queries
Route::group(['middleware' => ['auth:api', 'has.context.role.header']], function () {
    // USERS
    // Route::post('user', 'Api\UserController@update');

    // STRUCTURES
    Route::get('structures', 'Api\StructureController@index');
    Route::get('structures/{structure}', 'Api\StructureController@show');
    Route::post('structures/{structure}/missions', 'Api\StructureController@addMission');
    Route::delete('structures/{structure}/members/{member}', 'Api\StructureController@deleteMember');

    // INVITATIONS
    Route::get('invitations', 'Api\InvitationController@index');
    Route::post('invitations', 'Api\InvitationController@store');

    // MISSIONS
    Route::get('missions', 'Api\MissionController@index');
    Route::get('missions/{mission}/benevoles', 'Api\MissionController@benevoles');
    Route::put('missions/{mission}', 'Api\MissionController@update');
    Route::post('missions/{mission}/duplicate', 'Api\MissionController@duplicate');
    Route::delete('missions/{mission}', 'Api\MissionController@delete');

    // PROFILES
    Route::get('profiles', 'Api\ProfileController@index');

    // PARTICIPATIONS
    Route::get('participations', 'Api\ParticipationController@index');
    Route::get('participations/{participation}', 'Api\ParticipationController@show');
    Route::put('participations/{participation}', 'Api\ParticipationController@update');
    Route::put('participations/{participation}/decline', 'Api\ParticipationController@decline');

    // NOTIFICATIONS BENEVOLES
    Route::get('notifications-benevoles', 'Api\NotificationBenevoleController@index');
    Route::post('notifications-benevoles', 'Api\NotificationBenevoleController@store');

    // EXPORT
    Route::get('export/structures', 'Api\ExportController@structures');
    Route::get('export/missions', 'Api\ExportController@missions');
    Route::get('export/participations', 'Api\ExportController@participations');
    Route::get('export/profiles', 'Api\ExportController@profiles');

    // STATISTICS
    Route::get('statistics', 'Api\StatisticsController@dashboard');
    Route::get('statistics/organisations/{structure}', 'Api\StatisticsController@organisations');
    Route::get('statistics/missions/{mission}', 'Api\StatisticsController@missions');
    Route::get('statistics/reseaux/{reseau}', 'Api\StatisticsController@reseaux');

    // NUMBERS
    Route::get('numbers/global', 'Api\NumbersController@global');
    Route::get('numbers/trends/participations-by-activities', 'Api\NumbersController@trendsParticipationsByActivities');
    Route::get('numbers/trends/participations-by-departments', 'Api\NumbersController@trendsParticipationsByDepartments');

    // DOCUMENTS
    Route::get('documents', 'Api\DocumentController@index');

    // MISSIONS TEMPLATES
    Route::get('mission-templates/{missionTemplate}', 'Api\MissionTemplateController@show');
    Route::get('mission-templates', 'Api\MissionTemplateController@index');
    Route::post('mission-templates', 'Api\MissionTemplateController@store');
    Route::put('mission-templates/{missionTemplate}', 'Api\MissionTemplateController@update');
    Route::get('mission-templates/{missionTemplate}/statistics', 'Api\MissionTemplateController@statistics');
    Route::delete('mission-templates/{missionTemplate}', 'Api\MissionTemplateController@delete');

    // ACTIVITY LOGS
    Route::get('activity-logs', 'Api\ActivityLogController@index');

    // TERRITOIRES
    Route::post('territoires', 'Api\TerritoireController@store');
    Route::put('territoires/{territoire}', 'Api\TerritoireController@update');
    Route::get('territoires/{territoire}/statistics', 'Api\TerritoireController@statistics');
    Route::delete('territoires/{territoire}/responsables/{responsable}', 'Api\TerritoireController@deleteResponsable');

    // TEMOIGNAGES
    Route::get('temoignages', 'Api\TemoignageController@index');
    Route::get('temoignages/{temoignage}', 'Api\TemoignageController@show');

    // RESEAUX
    Route::get('reseaux', 'Api\ReseauController@index');

    // SNU
    Route::get('user/snu-actions', 'Api\ActionController@snuWaitingActions');

    // ACTIVITIES
    Route::get('activities', 'Api\ActivityController@index');

    // RESEAUX
    Route::put('reseaux/{reseau}', 'Api\ReseauController@update');
    Route::delete('reseaux/{reseau}/responsables/{responsable}', 'Api\ReseauController@deleteResponsable');
});

// ONLY ADMIN
Route::group(['middleware' => ['auth:api', 'is.admin']], function () {

    Route::post('settings/messages', 'Api\SettingController@updateMessages');
    Route::post('settings/general', 'Api\SettingController@updateGeneral');

    Route::get('notifications/{key}', 'Api\NotificationController@show');

    // DOMAINES D'ACTIONS
    Route::get('domaines', 'Api\DomaineController@index');
    Route::post('domaines', 'Api\DomaineController@store');
    Route::put('domaines/{domaine}', 'Api\DomaineController@update');
    Route::delete('domaines/{domaine}', 'Api\DomaineController@delete');

    // DOCUMENTS
    Route::get('documents/{document}', 'Api\DocumentController@show');
    Route::post('documents', 'Api\DocumentController@store');
    Route::put('documents/{document}', 'Api\DocumentController@update');
    Route::post('documents/{document}/notify', 'Api\DocumentController@notify');
    Route::delete('documents/{document}', 'Api\DocumentController@delete');

    // IMPERSONNATE
    Route::post('users/{user}/impersonate', 'Api\UserController@impersonate');

    // STRUCTURES
    Route::delete('structures/{structure}', 'Api\StructureController@delete');

    // RESEAUX
    Route::post('reseaux', 'Api\ReseauController@store');
    Route::delete('reseaux/{reseau}', 'Api\ReseauController@delete');

    // EXPORTS
    Route::get('export/territoires', 'Api\ExportController@territoires');
    Route::get('export/reseaux', 'Api\ExportController@reseaux');

    // SCRIPTS
    Route::post('scripts/migrate-organisation-missions', 'Api\ScriptController@migrateOrganisationMissions');
    Route::post('scripts/user-reset-context-role', 'Api\ScriptController@resetUserContextRole');

    // VOCABULARIES
    Route::post('/vocabularies/{vocabulary:slug}/terms', 'Api\TermController@store');
    Route::put('/vocabularies/{vocabulary:slug}/terms/{term}', 'Api\TermController@update');
    Route::get('/terms/{term}', 'Api\TermController@show');
    Route::delete('/terms/{term}', 'Api\TermController@delete');

    // ACTIVITIES
    Route::post('activities', 'Api\ActivityController@store');
    Route::put('activities/{activity}', 'Api\ActivityController@update');
    Route::get('activities/{activity}/statistics', 'Api\ActivityController@statistics');
    Route::delete('activities/{activity}', 'Api\ActivityController@delete');

    // TERRITOIRES
    Route::delete('territoires/{territoire}', 'Api\TerritoireController@delete');
});
