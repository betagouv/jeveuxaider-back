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
Route::get('temoignages/organisations/{structure}', 'Api\TemoignageController@forOrganisation');

Route::get('settings/messages', 'Api\SettingController@messages');
Route::get('settings/general', 'Api\SettingController@general');

Route::post('webhook/sendinblue', 'Api\WebhookController@sendinblue');

Route::get('emailable/verify/{email}', 'Api\EmailableController@verify');

Route::group(['middleware' => ['auth:api']], function () {
    Route::get('user', 'Api\UserController@me');
    Route::get('user/status', 'Api\UserController@status');
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
    Route::get('structures/{structure}/status', 'Api\StructureController@status');
    Route::post('structures/{structure}/unregister', 'Api\StructureController@unregister');
    Route::post('structures/{structure}/ask-to-unregister', 'Api\StructureController@askToUnregister');
    Route::get('structures/{structure}/responsables', 'Api\StructureController@responsables');

    Route::post('structures/{structure}/waiting-participations', 'Api\StructureController@waitingParticipations');
    Route::post('structures/{structure}/validate-waiting-participations', 'Api\StructureController@validateWaitingParticipations');

    Route::post('participations', 'Api\ParticipationController@store');
    Route::put('participations/{participation}/cancel', 'Api\ParticipationController@cancel');

    Route::post('user/password', 'Api\UserController@updatePassword');
    Route::get('user/mission/{mission}/has-participation', 'Api\UserController@hasParticipation');

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
    Route::delete('structures/{structure}/members/{user}', 'Api\StructureController@deleteMember');

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

    // BULK OPERATIONS
    Route::post('bulk-operation/participations/validate', 'Api\BulkOperationController@participationsValidate');
    Route::post('bulk-operation/participations/decline', 'Api\BulkOperationController@participationsDecline');

    // BATCH
    Route::get('/batch/{batchId}', 'Api\BatchController@show');

    // Activity classifier
    Route::post('/activity-classifier', 'Api\ActivityClassifierController@sortedOptions');
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

    // USER
    Route::get('users/{user}/logs', 'Api\UserController@logs');
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
    Route::get('scripts/activites-missions-libres/{activity}', 'Api\ScriptController@assignActivityToMissions');

    // VOCABULARIES
    Route::get('/vocabularies/{vocabulary:slug}', 'Api\VocabularyController@show');
    Route::post('/vocabularies/{vocabulary:slug}/terms', 'Api\TermController@store');
    Route::put('/vocabularies/{vocabulary:slug}/terms/{term}', 'Api\TermController@update');
    Route::get('/terms/{term}', 'Api\TermController@show');
    Route::delete('/terms/{term}', 'Api\TermController@delete');

    // ACTIVITIES
    Route::post('activities', 'Api\ActivityController@store');
    Route::put('activities/{activity}', 'Api\ActivityController@update');
    Route::get('activities/{activity}/statistics', 'Api\ActivityController@statistics');
    Route::delete('activities/{activity}', 'Api\ActivityController@delete');

    // TEMOIGNAGES
    Route::put('temoignages/{temoignage}', 'Api\TemoignageController@update');
    Route::put('temoignages/{temoignage}/publish', 'Api\TemoignageController@publish');
    Route::put('temoignages/{temoignage}/unpublish', 'Api\TemoignageController@unpublish');

    // TERRITOIRES
    Route::delete('territoires/{territoire}', 'Api\TerritoireController@delete');

    // Metatags
    Route::post('{modelType}/{modelId}/metatags', 'Api\MetatagsController@store');
    Route::put('metatags/{metatag}', 'Api\MetatagsController@update');
    Route::delete('metatags/{metatag}', 'Api\MetatagsController@delete');

    // ADD RESPONSABLE
    Route::post('territoires/{territoire}/responsables', 'Api\TerritoireController@addResponsable');
    Route::post('structures/{structure}/responsables', 'Api\StructureController@addResponsable');
    Route::post('reseaux/{reseau}/responsables', 'Api\ReseauController@addResponsable');

    // STATISTICS
    Route::get('statistics/organisations-waiting-by-departments', 'Api\NumbersController@organisationsWaitingByDepartments');
    Route::get('statistics/organisations-in-progress-by-departments', 'Api\NumbersController@organisationsInProgressByDepartments');
    Route::get('statistics/missions-waiting-by-departments', 'Api\NumbersController@missionsWaitingByDepartments');
    Route::get('statistics/missions-in-progress-by-departments', 'Api\NumbersController@missionsInProgressByDepartments');
    Route::get('statistics/missions-outdated-by-departments', 'Api\NumbersController@missionsOutdatedByDepartments');

    Route::get('statistics/api-engagement/outgoing-trafic', 'Api\ApiEngagementController@outgoingTrafic');
    Route::get('statistics/api-engagement/incoming-trafic', 'Api\ApiEngagementController@incomingTrafic');
    Route::get('statistics/api-engagement/outgoing-applies', 'Api\ApiEngagementController@outgoingApplies');
    Route::get('statistics/api-engagement/incoming-applies', 'Api\ApiEngagementController@incomingApplies');

    // ROLES
    Route::post('users/{user}/roles', 'Api\UserController@addRole');
    Route::delete('users/{user}/roles/{role}', 'Api\UserController@deleteRole');
});

// STATISTICS
Route::group(['middleware' => ['auth:api', 'is.admin.or.referent']], function () {

    // NOTES
    Route::get('/notes', 'Api\NoteController@all');
    Route::get('/{notable_type}/{notable_id}/notes', 'Api\NoteController@index')->where('notable_id', '[0-9]+');
    Route::get('/notes/{note}', 'Api\NoteController@index');
    Route::post('/{notable_type}/{notable_id}/notes', 'Api\NoteController@store')->where('notable_id', '[0-9]+');
    Route::put('/notes/{note}', 'Api\NoteController@update');
    Route::delete('/notes/{note}', 'Api\NoteController@delete');

    // NUMBERS
    Route::get('statistics/overview-quick-glance', 'Api\NumbersController@overviewQuickGlance');
    Route::get('statistics/overview-missions', 'Api\NumbersController@overviewMissions');
    Route::get('statistics/overview-places', 'Api\NumbersController@overviewPlaces');
    Route::get('statistics/overview-organisations', 'Api\NumbersController@overviewOrganisations');
    Route::get('statistics/overview-utilisateurs', 'Api\NumbersController@overviewUtilisateurs');
    Route::get('statistics/overview-participations', 'Api\NumbersController@overviewParticipations');

    Route::get('statistics/overview-api-engagement', 'Api\NumbersController@overviewAPIEngagement');
    Route::get('statistics/overview-api-engagement-entrant', 'Api\NumbersController@overviewAPIEngagementEntrant');
    Route::get('statistics/overview-api-engagement-entrant-details', 'Api\NumbersController@overviewAPIEngagementEntrantDetails');
    Route::get('statistics/overview-api-engagement-sortant', 'Api\NumbersController@overviewAPIEngagementSortant');
    Route::get('statistics/overview-api-engagement-sortant-details', 'Api\NumbersController@overviewAPIEngagementSortantDetails');

    Route::get('statistics/global/organisations', 'Api\NumbersController@globalOrganisations');
    Route::get('statistics/global/missions', 'Api\NumbersController@globalMissions');
    Route::get('statistics/global/participations', 'Api\NumbersController@globalParticipations');
    Route::get('statistics/global/utilisateurs', 'Api\NumbersController@globalUtilisateurs');
    Route::get('statistics/global/places', 'Api\NumbersController@globalPlaces');

    Route::get('statistics/participations-by-activities', 'Api\NumbersController@participationsByActivities');
    Route::get('statistics/participations-by-mission-templates', 'Api\NumbersController@participationsByMissionTemplates');
    Route::get('statistics/participations-by-missions', 'Api\NumbersController@participationsByMissions');
    Route::get('statistics/participations-by-organisations', 'Api\NumbersController@participationsByOrganisations');
    Route::get('statistics/participations-by-reseaux', 'Api\NumbersController@participationsByReseaux');
    Route::get('statistics/participations-by-states', 'Api\NumbersController@participationsByStates');
    Route::get('statistics/participations-by-domaines', 'Api\NumbersController@participationsByDomaines');
    Route::get('statistics/participations-by-reseaux', 'Api\NumbersController@participationsByReseaux');
    Route::get('statistics/participations-canceled-by-benevoles', 'Api\NumbersController@participationsCanceledByBenevoles');
    Route::get('statistics/participations-refused-by-responsables', 'Api\NumbersController@participationsRefusedByResponsables');
    Route::get('statistics/participations-delays-by-registrations', 'Api\NumbersController@participationsDelaysByRegistrations');

    Route::get('statistics/missions-by-states', 'Api\NumbersController@missionsByStates');
    Route::get('statistics/missions-by-types', 'Api\NumbersController@missionsByTypes');
    Route::get('statistics/missions-by-activities', 'Api\NumbersController@missionsByActivities');
    Route::get('statistics/missions-by-templates', 'Api\NumbersController@missionsByTemplates');
    Route::get('statistics/missions-by-domaines', 'Api\NumbersController@missionsByDomaines');
    Route::get('statistics/missions-by-organisations', 'Api\NumbersController@missionsByOrganisations');
    Route::get('statistics/missions-by-reseaux', 'Api\NumbersController@missionsByReseaux');
    Route::get('statistics/missions-by-template-types', 'Api\NumbersController@missionsByTemplateTypes');

    Route::get('statistics/organisations-by-states', 'Api\NumbersController@organisationsByStates');
    Route::get('statistics/organisations-by-types', 'Api\NumbersController@organisationsByTypes');
    Route::get('statistics/organisations-by-domaines', 'Api\NumbersController@organisationsByDomaines');
    Route::get('statistics/organisations-by-reseaux', 'Api\NumbersController@organisationsByReseaux');

    Route::get('statistics/places-by-reseaux', 'Api\NumbersController@placesByReseaux');
    Route::get('statistics/places-by-organisations', 'Api\NumbersController@placesByOrganisations');
    Route::get('statistics/places-by-missions', 'Api\NumbersController@placesByMissions');
    Route::get('statistics/places-by-domaines', 'Api\NumbersController@placesByDomaines');
    Route::get('statistics/places-by-activities', 'Api\NumbersController@placesByActivities');

    Route::get('statistics/utilisateurs-by-domaines', 'Api\NumbersController@utilisateursByDomaines');
    Route::get('statistics/utilisateurs-with-participations', 'Api\NumbersController@utilisateursWithParticipations');

    Route::get('statistics/participations-waiting-by-organisations', 'Api\NumbersController@participationsWaitingByOrganisations');
    Route::get('statistics/participations-refused-by-organisations', 'Api\NumbersController@participationsRefusedByOrganisations');
    Route::get('statistics/participations-canceled-by-organisations', 'Api\NumbersController@participationsCanceledByOrganisations');
    Route::get('statistics/participations-in-progress-by-organisations', 'Api\NumbersController@participationsInProgressByOrganisations');
    Route::get('statistics/missions-outdated-by-organisations', 'Api\NumbersController@missionsOutdatedByOrganisations');

    // CHARTS
    Route::get('charts/organisations-by-date', 'Api\ChartsController@organisationsByDate');
    Route::get('charts/missions-by-date', 'Api\ChartsController@missionsByDate');
    Route::get('charts/participations-by-date', 'Api\ChartsController@participationsByDate');
    Route::get('charts/participations-conversion-by-date', 'Api\ChartsController@participationsConversionByDate');
    Route::get('charts/utilisateurs-by-date', 'Api\ChartsController@utilisateursByDate');

    // INDICATEURS
    Route::get('statistics/structures-by-month', 'Api\NumbersController@structuresByMonth');
    Route::get('statistics/missions-by-month', 'Api\NumbersController@missionsByMonth');
    Route::get('statistics/participations-by-month', 'Api\NumbersController@participationsByMonth');
    Route::get('statistics/users-by-month', 'Api\NumbersController@usersByMonth');

    Route::get('statistics/structures-by-year', 'Api\NumbersController@structuresByYear');
    Route::get('statistics/missions-by-year', 'Api\NumbersController@missionsByYear');
    Route::get('statistics/participations-by-year', 'Api\NumbersController@participationsByYear');
    Route::get('statistics/users-by-year', 'Api\NumbersController@usersByYear');

    // CONVERSATIONS
    Route::post('conversations', 'Api\ConversationsController@store');
});


// STATISTICS PUBLIC
Route::group(['prefix' => '/statistics/public'], function () {
    Route::get('/overview-quick-glance', 'Api\StatisticsPublicController@overviewQuickGlance');
    Route::get('/overview-participations', 'Api\StatisticsPublicController@overviewParticipations');
    Route::get('/overview-utilisateurs', 'Api\StatisticsPublicController@overviewUtilisateurs');
    Route::get('/overview-organisations', 'Api\StatisticsPublicController@overviewOrganisations');
    Route::get('/overview-missions', 'Api\StatisticsPublicController@overviewMissions');
    Route::get('/overview-places', 'Api\StatisticsPublicController@overviewPlaces');
    Route::get('/overview-api-engagement', 'Api\StatisticsPublicController@overviewAPIEngagement');

    Route::get('/global/participations', 'Api\StatisticsPublicController@globalParticipations');
    Route::get('/participations-by-date', 'Api\StatisticsPublicController@participationsByDate');
    Route::get('/participations-by-reseaux', 'Api\StatisticsPublicController@participationsByReseaux');
    Route::get('/participations-by-activities', 'Api\StatisticsPublicController@participationsByActivities');
    Route::get('/participations-by-domaines', 'Api\StatisticsPublicController@participationsByDomaines');
    Route::get('/participations-by-organisations', 'Api\StatisticsPublicController@participationsByOrganisations');
    Route::get('/participations-canceled-by-benevoles', 'Api\StatisticsPublicController@participationsCanceledByBenevoles');
    Route::get('/participations-delays-by-registrations', 'Api\StatisticsPublicController@participationsDelaysByRegistrations');
    Route::get('/participations-refused-by-responsables', 'Api\StatisticsPublicController@participationsRefusedByResponsables');
    Route::get('/temoignages-by-grades', 'Api\StatisticsPublicController@temoignagesByGrades');

    Route::get('/global/utilisateurs', 'Api\StatisticsPublicController@globalUtilisateurs');
    Route::get('/utilisateurs-by-date', 'Api\StatisticsPublicController@utilisateursByDate');
    Route::get('/utilisateurs-by-domaines', 'Api\StatisticsPublicController@utilisateursByDomaines');

    Route::get('/global/organisations', 'Api\StatisticsPublicController@globalOrganisations');
    Route::get('/organisations-by-date', 'Api\StatisticsPublicController@organisationsByDate');
    Route::get('/organisations-by-states', 'Api\StatisticsPublicController@organisationsByStates');
    Route::get('/organisations-by-types', 'Api\StatisticsPublicController@organisationsByTypes');
    Route::get('/organisations-by-domaines', 'Api\StatisticsPublicController@organisationsByDomaines');
    Route::get('/organisations-by-reseaux', 'Api\StatisticsPublicController@organisationsByReseaux');

    Route::get('/global/missions', 'Api\StatisticsPublicController@globalMissions');
    Route::get('/missions-by-date', 'Api\StatisticsPublicController@missionsByDate');
    Route::get('/missions-by-states', 'Api\StatisticsPublicController@missionsByStates');
    Route::get('/missions-by-types', 'Api\StatisticsPublicController@missionsByTypes');
    Route::get('/missions-by-activities', 'Api\StatisticsPublicController@missionsByActivities');
    Route::get('/missions-by-templates', 'Api\StatisticsPublicController@missionsByTemplates');
    Route::get('/missions-by-domaines', 'Api\StatisticsPublicController@missionsByDomaines');
    Route::get('/missions-by-organisations', 'Api\StatisticsPublicController@missionsByOrganisations');
    Route::get('/missions-by-reseaux', 'Api\StatisticsPublicController@missionsByReseaux');
    Route::get('/missions-by-template-types', 'Api\StatisticsPublicController@missionsByTemplateTypes');

    Route::get('/global/places', 'Api\StatisticsPublicController@globalPlaces');
    Route::get('/places-by-reseaux', 'Api\StatisticsPublicController@placesByReseaux');
    Route::get('/places-by-organisations', 'Api\StatisticsPublicController@placesByOrganisations');
    Route::get('/places-by-missions', 'Api\StatisticsPublicController@placesByMissions');
    Route::get('/places-by-domaines', 'Api\StatisticsPublicController@placesByDomaines');
    Route::get('/places-by-activities', 'Api\StatisticsPublicController@placesByActivities');
});
