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

Route::get('/missions/{slugOrId}/view', 'Api\MissionController@view');
Route::get('/territoires/{territoire:slug}/view', 'Api\TerritoireController@view');
Route::get('/reseaux/{reseau:slug}/view', 'Api\ReseauController@view');

// AUTH
Route::post('register/volontaire', 'Api\PassportController@registerVolontaire');
Route::post('register/responsable', 'Api\PassportController@registerResponsable');
Route::post('password/forgot', 'Api\PassportController@forgotPassword');
Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');

// Route::get('missions/prioritaires', 'Api\MissionController@prioritaires');
Route::get('missions/{mission}/similar', 'Api\MissionController@similar');
Route::post('missions/similar-for-api', 'Api\MissionController@similarForApi');
Route::get('associations/{slugOrId}', 'Api\StructureController@associationSlugOrId');
Route::get('structures/{structure}/activities', 'Api\StructureController@activities');
Route::get('reseaux/{reseau}/activities', 'Api\ReseauController@activities');

Route::get('territoires/{name}/exist', 'Api\TerritoireController@exist');
Route::get('structures/{rnaOrName}/exist', 'Api\StructureController@exist');
// Route::get('structures/{structure}/available-missions', 'Api\StructureController@availableMissions');

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
// Route::get('territoires/{slugOrId}', 'Api\TerritoireController@show');
Route::get('territoires/{territoire}/available-cities', 'Api\TerritoireController@availableCities');

Route::post('reseaux/lead', 'Api\ReseauController@lead');
// Route::get('reseaux/{reseau}/structures', 'Api\ReseauController@structures');

Route::get('notification-temoignage/{token}', 'Api\NotificationTemoignageController@show');

Route::post('temoignages', 'Api\TemoignageController@store');
Route::get('temoignages/organisations/{structure}', 'Api\TemoignageController@forOrganisation');
Route::get('temoignages/reseaux/{reseau}', 'Api\TemoignageController@forReseau');

Route::get('settings/messages', 'Api\SettingController@messages');
Route::get('settings/general', 'Api\SettingController@general');

Route::post('webhook/sendinblue', 'Api\WebhookController@sendinblue');

Route::get('emailable/verify/{email}', 'Api\EmailableController@verify');

Route::get('organisations/popular', 'Api\StructureController@popular');

Route::get('structures/{structure}/score', 'Api\StructureController@score');

Route::post('api-engagement/associations/search', 'Api\ApiEngagementController@searchAssociations');

Route::post('user/archive/exist', 'Api\UserController@checkUserArchiveExist');
Route::post('user/archive/send-code', 'Api\UserController@sendUserArchiveCode');
Route::post('user/archive/validate-code', 'Api\UserController@validateUserArchiveCode');

Route::group(['middleware' => ['auth:api', 'is.not.blocked']], function () {
    Route::get('user', 'Api\UserController@me');
    Route::get('user/status', 'Api\UserController@status');
    Route::get('user/unread-messages', 'Api\UserController@unreadMessages');
    Route::get('user/last-read-conversation', 'Api\UserController@lastReadConversation');
    Route::get('user/participations', 'Api\UserController@participations');
    Route::post('user/anonymize', 'Api\UserController@anonymize');
    Route::post('user/visible', 'Api\UserController@visible');
    Route::post('user/invisible', 'Api\UserController@invisible');
    Route::put('user', 'Api\UserController@update');
    Route::put('user/switch-role', 'Api\UserController@switchRole');
    Route::get('profiles/{profile}', 'Api\ProfileController@show');
    Route::put('profiles/{profile}', 'Api\ProfileController@update');
    Route::put('profiles/{profile}/activity/{activity}/attach', 'Api\ProfileController@attachActivity');
    Route::put('profiles/{profile}/activity/{activity}/detach', 'Api\ProfileController@detachActivity');

    Route::get('user/actions', 'Api\UserActionController@index');
    Route::get('user/actions/benevole', 'Api\UserActionController@benevole');
    Route::get('users/{user}/roles', 'Api\UserController@roles');

    Route::get('user/favoris', 'Api\UserController@favoris');
    Route::get('user/notifications', 'Api\UserController@notifications');
    Route::post('user/notifications/mark-all-as-read', 'Api\UserController@notificationsMarkAllAsRead');
    Route::post('user/notifications/{notification}/mark-as-read', 'Api\UserController@notificationsMarkAsRead');
    Route::get('user/unread-notifications', 'Api\UserController@unreadNotifications');

    Route::get('user/alerts', 'Api\UserAlertController@index');
    Route::post('user/alerts', 'Api\UserAlertController@store');
    Route::put('user/alerts/{alert}', 'Api\UserAlertController@update');
    Route::delete('user/alerts/{alert}', 'Api\UserAlertController@delete');

    Route::get('medias', 'Api\MediaController@index');
    Route::post('medias/{modelType}/{modelId}/{collectionName}', 'Api\MediaController@store');
    Route::put('medias/{media}', 'Api\MediaController@update');
    Route::delete('medias/{media}', 'Api\MediaController@delete');

    Route::post('structure', 'Api\StructureController@store');
    Route::put('structures/{structure}', 'Api\StructureController@update');
    Route::get('structures/{structure}/status', 'Api\StructureController@status');
    Route::post('structures/{structure}/unregister', 'Api\StructureController@unregister');
    Route::post('structures/{structure}/ask-to-unregister', 'Api\StructureController@askToUnregister');


    Route::get('structures/{structure}/tags', 'Api\StructureTagController@index');
    Route::post('structures/{structure}/tags', 'Api\StructureTagController@store');
    Route::put('structures/{structure}/tags/{structureTag}', 'Api\StructureTagController@update');
    Route::delete('structures/{structure}/tags/{structureTag}', 'Api\StructureTagController@delete');

    // Route::post('structures/{structure}/waiting-participations', 'Api\StructureController@waitingParticipations'); Plus utilisé ?
    Route::post('structures/{structure}/validate-waiting-participations', 'Api\StructureController@validateWaitingParticipations');

    Route::post('participations', 'Api\ParticipationController@store');
    Route::put('participations/{participation}/cancel-by-benevole', 'Api\ParticipationController@cancelByBenevole');
    Route::put('participations/{participation}/validate-by-benevole', 'Api\ParticipationController@validateByBenevole');

    Route::post('missions/{mission}/share', 'Api\MissionController@share');
    Route::post('missions/{mission}/waiting-list', 'Api\MissionController@addUserToWaitingList');
    Route::delete('missions/{mission}/waiting-list', 'Api\MissionController@removeUserToWaitingList');

    Route::post('missions/{mission}/favorite', 'Api\MissionController@addToFavorite');
    Route::delete('missions/{mission}/favorite', 'Api\MissionController@removeFromFavorite');

    // Route::post('user/password', 'Api\UserController@updatePassword');
    Route::post('user/email', 'Api\FormUserController@email');
    Route::post('user/password', 'Api\FormUserController@password');
    Route::get('user/mission/{mission}/has-participation', 'Api\UserController@hasParticipation');

    // MESSAGERIE
    Route::get('conversations', 'Api\ConversationsController@index');
    Route::get('conversations/{conversation}', 'Api\ConversationsController@show')->where('conversation', '[0-9]+');
    Route::get('conversations/{conversation}/messages', 'Api\ConversationsController@messages');
    Route::post('conversations/{conversation}/messages', 'Api\ConversationsController@storeMessage');
    Route::post('conversations/{conversation}/archive', 'Api\ConversationsController@archive');
    Route::post('conversations/{conversation}/unarchive', 'Api\ConversationsController@unarchive');

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
    Route::get('structures/{structure}/invitations', 'Api\StructureController@invitations');
    Route::delete('structures/{structure}/members/{user}', 'Api\StructureController@deleteMember');
    Route::get('structures/{structure}/responsables', 'Api\StructureController@responsables');

    Route::post('structures/{structure}/v2/missions', 'Api\FormMissionController@store');

    Route::get('/missions/{mission}/show', 'Api\FormMissionController@show');
    Route::put('/missions/{mission}/title', 'Api\FormMissionController@updateTitle');
    Route::put('/missions/{mission}/visuel', 'Api\FormMissionController@updateVisuel');
    Route::put('/missions/{mission}/informations', 'Api\FormMissionController@updateInformations');
    Route::put('/missions/{mission}/dates', 'Api\FormMissionController@updateDates');
    Route::put('/missions/{mission}/lieux', 'Api\FormMissionController@updateLieux');
    Route::put('/missions/{mission}/benevoles', 'Api\FormMissionController@updateBenevoles');
    Route::put('/missions/{mission}/benevoles-informations', 'Api\FormMissionController@updateBenevolesInformations');
    Route::put('/missions/{mission}/responsables', 'Api\FormMissionController@updateResponsables');

    Route::put('/missions/{mission}/publish', 'Api\MissionController@publish');

    // INVITATIONS
    Route::post('invitations', 'Api\InvitationController@store');

    // MISSIONS
    Route::get('missions', 'Api\MissionController@index');
    Route::get('missions/{mission}', 'Api\MissionController@show');
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
    Route::post('participations/{participation}/tags/{structureTag}/attach', 'Api\ParticipationController@attachStructureTag');
    Route::post('participations/{participation}/tags/{structureTag}/detach', 'Api\ParticipationController@detachStructureTag');

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
    Route::get('statistics/profiles/{profile}', 'Api\StatisticsController@profiles');

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
    Route::get('activity-logs/structure/{structure}', 'Api\ActivityLogController@structure');
    Route::get('activity-logs/structure/{structure}/states', 'Api\ActivityLogController@structureStatesChanges');
    Route::get('activity-logs/mission/{mission}', 'Api\ActivityLogController@mission');
    Route::get('activity-logs/mission/{mission}/states', 'Api\ActivityLogController@missionStatesChanges');
    Route::get('activity-logs/participation/{participation}', 'Api\ActivityLogController@participation');
    Route::get('activity-logs/participation/{participation}/states', 'Api\ActivityLogController@participationStatesChanges');

    // TERRITOIRES
    Route::get('territoires/{territoire}', 'Api\TerritoireController@show');
    Route::put('territoires/{territoire}', 'Api\TerritoireController@update');
    Route::get('territoires/{territoire}/statistics', 'Api\TerritoireController@statistics');
    Route::get('territoires/{territoire}/invitations', 'Api\TerritoireController@invitations');
    Route::delete('territoires/{territoire}/responsables/{responsable}', 'Api\TerritoireController@deleteResponsable');

    // TEMOIGNAGES
    Route::get('temoignages', 'Api\TemoignageController@index');
    Route::get('temoignages/{temoignage}', 'Api\TemoignageController@show');

    // RESEAUX
    Route::get('reseaux', 'Api\ReseauController@index');

    // SNU
    Route::get('user/snu-actions', 'Api\UserActionController@snuWaitingActions');

    // ACTIVITIES
    Route::get('activities', 'Api\ActivityController@index');

    // RESEAUX
    Route::get('reseaux/{reseau}', 'Api\ReseauController@show');
    Route::put('reseaux/{reseau}', 'Api\ReseauController@update');
    Route::get('reseaux/{reseau}/invitations-responsables', 'Api\ReseauController@invitationsResponsables');
    Route::get('reseaux/{reseau}/invitations-antennes', 'Api\ReseauController@invitationsAntennes');
    Route::delete('reseaux/{reseau}/responsables/{responsable}', 'Api\ReseauController@deleteResponsable');

    // BULK OPERATIONS
    Route::post('bulk-operation/participations/validate', 'Api\BulkOperationController@participationsValidate');
    Route::post('bulk-operation/participations/decline', 'Api\BulkOperationController@participationsDecline');

    // BATCH
    Route::get('/batch/{batchId}', 'Api\BatchController@show');
    Route::post('/batch/{batchId}/cancel', 'Api\BatchController@cancel');

    // Activity classifier
    Route::post('/activity-classifier', 'Api\ActivityClassifierController@sortedOptions');

    // MESSAGES TEMPLATES
    Route::get('message-templates', 'Api\MessageTemplateController@index');
    Route::post('message-templates', 'Api\MessageTemplateController@store');
    Route::put('message-templates/{messageTemplate}', 'Api\MessageTemplateController@update');
    Route::post('message-templates/{messageTemplate}/duplicate', 'Api\MessageTemplateController@duplicate');
    Route::delete('message-templates/{messageTemplate}', 'Api\MessageTemplateController@delete');


    // STATISTICS
    Route::get('statistics/overview-quick-glance', 'Api\NumbersController@overviewQuickGlance');
    Route::get('statistics/overview-missions', 'Api\NumbersController@overviewMissions');
    Route::get('statistics/overview-places', 'Api\NumbersController@overviewPlaces');
    Route::get('statistics/overview-organisations', 'Api\NumbersController@overviewOrganisations');
    Route::get('statistics/overview-participations', 'Api\NumbersController@overviewParticipations');

    Route::get('statistics/global/organisations', 'Api\NumbersController@globalOrganisations');
    Route::get('statistics/global/missions', 'Api\NumbersController@globalMissions');
    Route::get('statistics/global/participations', 'Api\NumbersController@globalParticipations');
    Route::get('statistics/global/places', 'Api\NumbersController@globalPlaces');

    Route::get('statistics/participations-by-activities', 'Api\NumbersController@participationsByActivities');
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
    Route::get('statistics/missions-by-template-types', 'Api\NumbersController@missionsByTemplateTypes');

    Route::get('statistics/organisations-by-states', 'Api\NumbersController@organisationsByStates');
    Route::get('statistics/organisations-by-types', 'Api\NumbersController@organisationsByTypes');
    Route::get('statistics/organisations-by-domaines', 'Api\NumbersController@organisationsByDomaines');

    Route::get('statistics/places-by-organisations', 'Api\NumbersController@placesByOrganisations');
    Route::get('statistics/places-by-missions', 'Api\NumbersController@placesByMissions');
    Route::get('statistics/places-by-domaines', 'Api\NumbersController@placesByDomaines');
    Route::get('statistics/places-by-activities', 'Api\NumbersController@placesByActivities');

    Route::get('statistics/structures-by-month', 'Api\NumbersController@structuresByMonth');
    Route::get('statistics/missions-by-month', 'Api\NumbersController@missionsByMonth');
    Route::get('statistics/participations-by-month', 'Api\NumbersController@participationsByMonth');

    Route::get('statistics/structures-by-year', 'Api\NumbersController@structuresByYear');
    Route::get('statistics/missions-by-year', 'Api\NumbersController@missionsByYear');
    Route::get('statistics/participations-by-year', 'Api\NumbersController@participationsByYear');

    // CHARTS
    Route::get('charts/organisations-by-date', 'Api\ChartsController@organisationsByDate');
    Route::get('charts/missions-by-date', 'Api\ChartsController@missionsByDate');
    Route::get('charts/participations-by-date', 'Api\ChartsController@participationsByDate');
    Route::get('charts/participations-by-period', 'Api\ChartsController@participationsByPeriod');
    Route::get('charts/missions-by-period', 'Api\ChartsController@missionsByPeriod');
    Route::get('charts/organisations-by-period', 'Api\ChartsController@organisationsByPeriod');
    Route::get('charts/utilisateurs-by-period', 'Api\ChartsController@utilisateursByPeriod');
    Route::get('charts/participations-conversion-by-date', 'Api\ChartsController@participationsConversionByDate');

});

// ONLY ADMIN
Route::group(['middleware' => ['auth:api', 'is.admin']], function () {
    Route::post('settings/messages', 'Api\SettingController@updateMessages');
    Route::post('settings/general', 'Api\SettingController@updateGeneral');

    Route::get('notifications/{key}', 'Api\NotificationController@show');
    Route::post('notifications/{key}/test', 'Api\NotificationController@test');

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

    // Rules
    Route::get('rules', 'Api\RuleController@index');
    Route::get('rules/{rule}', 'Api\RuleController@show');
    Route::get('rules/{rule}/pending-items', 'Api\RuleController@pendingItems');
    Route::post('rules', 'Api\RuleController@store');
    Route::post('rules/{rule}/batch', 'Api\RuleController@batch');
    Route::put('rules/{rule}', 'Api\RuleController@update');
    Route::delete('rules/{rule}', 'Api\RuleController@delete');

    // USER
    Route::get('users/{user}/actions', 'Api\UserController@actions');
    Route::post('users/{user}/impersonate', 'Api\UserController@impersonate');
    Route::post('users/{user}/ban', 'Api\UserController@ban');
    Route::post('users/{user}/unban', 'Api\UserController@unban');
    // Route::post('users/{user}/archive', 'Api\UserController@archive');
    // Route::post('users/{user}/unarchive', 'Api\UserController@unarchive');

    // STRUCTURES
    Route::delete('structures/{structure}', 'Api\StructureController@delete');

    // RESEAUX
    Route::post('reseaux', 'Api\ReseauController@store');
    Route::delete('reseaux/{reseau}', 'Api\ReseauController@delete');

    // EXPORTS
    Route::get('export/territoires', 'Api\ExportController@territoires');
    Route::get('export/reseaux', 'Api\ExportController@reseaux');

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
    Route::post('territoires', 'Api\TerritoireController@store');
    Route::delete('territoires/{territoire}', 'Api\TerritoireController@delete');

    // INVITATIONS
    Route::get('invitations', 'Api\InvitationController@index');

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

    Route::post('profiles/{profile}/setMissionsIsActive', 'Api\ProfileController@setMissionsIsActiveForResponsable');

    // ADMINISTRATION
    Route::get('administration/goals', 'Api\AdministrationController@goals');
    Route::get('administration/missions-trending', 'Api\AdministrationController@missionsTrending');
    Route::get('administration/organisations-trending', 'Api\AdministrationController@organisationsTrending');
    Route::get('administration/topito-admins', 'Api\AdministrationController@topitoAdmins');
    Route::get('administration/topito-referents', 'Api\AdministrationController@topitoReferents');

    // SUPPORT
    Route::get('support/referents/overview', 'Api\SupportController@referentsOverview');
    Route::get('support/responsables/overview', 'Api\SupportController@responsablesOverview');
    Route::get('support/referents/waiting-actions', 'Api\SupportController@referentsWaitingActions');
    Route::get('support/referents/activity-logs', 'Api\SupportController@referentsActivityLogs');
    Route::get('support/responsables/participations-to-be-treated', 'Api\SupportController@responsablesParticipationsToBeTreated');
    Route::get('support/responsables/missions-outdated', 'Api\SupportController@responsablesMissionsOutdated');

    // SCRIPTS
    Route::post('scripts/transfert-organisation', 'Api\ScriptController@transfertOrganisation');
    Route::post('scripts/user-reset-context-role', 'Api\ScriptController@resetUserContextRole');
    // Route::get('scripts/activites-missions-libres/{activity}', 'Api\ScriptController@assignActivityToMissions');
    Route::post('support/actions/generate-password-reset-link', 'Api\SupportController@generatePasswordResetLink');

    Route::get('support/contents/doublons-territoires', 'Api\SupportController@doublonsTerritoires');
    Route::post('support/contents/doublons-territoires/list', 'Api\SupportController@fetchDoublonsTerritoires');
    Route::get('support/contents/doublons-territoires/export', 'Api\SupportController@exportDoublonsTerritoires');
    Route::get('support/contents/doublons-organisations', 'Api\SupportController@doublonsOrganisations');
    Route::post('support/contents/doublons-organisations/list', 'Api\SupportController@fetchDoublonsOrganisations');


    Route::get('activity-logs', 'Api\ActivityLogController@index');
    Route::get('activity-logs/{activityLog}', 'Api\ActivityLogController@show');

    Route::get('statistics/global/moderations', 'Api\NumbersController@globalModerations');
    Route::get('statistics/activity-logs/admins-vs-referents', 'Api\NumbersController@activityAdminsVsReferents');

    // UTILISATEURS ARCHIVÉS
    Route::get('archived-users', 'Api\UserArchivedDatasController@index');
    Route::delete('archived-users/{userArchivedDatas}', 'Api\UserArchivedDatasController@delete');

    // Route::get('qsl3df-haz7uif5a-ozf44sqd9f-ai0eha243', 'Api\DebugController@debug');
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
    Route::get('statistics/overview-utilisateurs', 'Api\NumbersController@overviewUtilisateurs');
    Route::get('statistics/overview-api-engagement', 'Api\NumbersController@overviewAPIEngagement');
    Route::get('statistics/overview-api-engagement-entrant', 'Api\NumbersController@overviewAPIEngagementEntrant');
    Route::get('statistics/overview-api-engagement-entrant-details', 'Api\NumbersController@overviewAPIEngagementEntrantDetails');
    Route::get('statistics/overview-api-engagement-sortant', 'Api\NumbersController@overviewAPIEngagementSortant');
    Route::get('statistics/overview-api-engagement-sortant-details', 'Api\NumbersController@overviewAPIEngagementSortantDetails');
    Route::get('statistics/global/utilisateurs', 'Api\NumbersController@globalUtilisateurs');
    Route::get('statistics/missions-by-reseaux', 'Api\NumbersController@missionsByReseaux');
    Route::get('statistics/organisations-by-reseaux', 'Api\NumbersController@organisationsByReseaux');
    Route::get('statistics/places-by-reseaux', 'Api\NumbersController@placesByReseaux');
    Route::get('statistics/utilisateurs-by-domaines', 'Api\NumbersController@utilisateursByDomaines');
    Route::get('statistics/utilisateurs-by-activities', 'Api\NumbersController@utilisateursByActivities');
    Route::get('statistics/utilisateurs-with-participations', 'Api\NumbersController@utilisateursWithParticipations');
    Route::get('statistics/utilisateurs-by-age', 'Api\NumbersController@utilisateursByAge');
    Route::get('statistics/participations-waiting-by-organisations', 'Api\NumbersController@participationsWaitingByOrganisations');
    Route::get('statistics/participations-refused-by-organisations', 'Api\NumbersController@participationsRefusedByOrganisations');
    Route::get('statistics/participations-canceled-by-organisations', 'Api\NumbersController@participationsCanceledByOrganisations');
    Route::get('statistics/participations-in-progress-by-organisations', 'Api\NumbersController@participationsInProgressByOrganisations');
    Route::get('statistics/missions-outdated-by-organisations', 'Api\NumbersController@missionsOutdatedByOrganisations');
    Route::get('charts/utilisateurs-by-date', 'Api\ChartsController@utilisateursByDate');

    // INDICATEURS
    // Route::get('statistics/structures-by-month', 'Api\NumbersController@structuresByMonth');
    Route::get('statistics/users-by-month', 'Api\NumbersController@usersByMonth');
    Route::get('statistics/users-by-year', 'Api\NumbersController@usersByYear');

    // CONVERSATIONS
    Route::post('conversations', 'Api\ConversationsController@store');

    // ALGOLIA
    Route::post('algolia/missions', 'Api\AlgoliaController@missions');
    Route::post('algolia/organisations', 'Api\AlgoliaController@organisations');
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
    Route::get('/participations-by-period', 'Api\StatisticsPublicController@participationsByPeriod');
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
    Route::get('/utilisateurs-by-period', 'Api\StatisticsPublicController@utilisateursByPeriod');
    Route::get('/utilisateurs-by-domaines', 'Api\StatisticsPublicController@utilisateursByDomaines');
    Route::get('/utilisateurs-by-activities', 'Api\StatisticsPublicController@utilisateursByActivities');
    Route::get('/utilisateurs-by-age', 'Api\StatisticsPublicController@utilisateursByAge');

    Route::get('/global/organisations', 'Api\StatisticsPublicController@globalOrganisations');
    Route::get('/organisations-by-date', 'Api\StatisticsPublicController@organisationsByDate');
    Route::get('/organisations-by-period', 'Api\StatisticsPublicController@organisationsByPeriod');
    Route::get('/organisations-by-states', 'Api\StatisticsPublicController@organisationsByStates');
    Route::get('/organisations-by-types', 'Api\StatisticsPublicController@organisationsByTypes');
    Route::get('/organisations-by-domaines', 'Api\StatisticsPublicController@organisationsByDomaines');
    Route::get('/organisations-by-reseaux', 'Api\StatisticsPublicController@organisationsByReseaux');

    Route::get('/global/missions', 'Api\StatisticsPublicController@globalMissions');
    Route::get('/missions-by-date', 'Api\StatisticsPublicController@missionsByDate');
    Route::get('/missions-by-period', 'Api\StatisticsPublicController@missionsByPeriod');
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
