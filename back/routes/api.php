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
// Route::post('password/forgot', 'Api\PassportController@forgotPassword');
// Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');

// Route::get('faqs', 'Api\FaqController@index');
Route::get('page/{page}', 'Api\PageController@show');

Route::get('missions/promoted', 'Api\MissionController@promotedToFrontPage');
Route::get('mission/{mission}', 'Api\MissionController@show');
Route::get('mission/{mission}/similar', 'Api\MissionController@similar');
Route::get('association/{slugOrId}', 'Api\StructureController@associationSlugOrId');

Route::get('structure/{rnaOrName}/exist', 'Api\StructureController@exist');
Route::get('structure/{structure}/availableMissions', 'Api\StructureController@availableMissions');

// Route::get('bootstrap', 'Api\ConfigController@bootstrap');
// Route::get('sitemap', 'Api\ConfigController@sitemap');

Route::get('thematique/{slugOrId}', 'Api\ThematiqueController@show');
Route::get('thematique/{slugOrId}/statistics', 'Api\ThematiqueController@statistics');

// Route::get('statistics/global', 'Api\StatisticsController@global');

// Route::post('sendinblue/contact', 'Api\SendInBlueController@store');

// Route::get('invitation/{token}', 'Api\InvitationController@show');
// Route::post('invitation/{token}/register', 'Api\InvitationController@register');

// Route::post('firstname', 'Api\ProfileController@firstname');

Route::get('franceconnect/login-authorize', 'Auth\FranceConnectController@oauthLoginAuthorize');
Route::get('franceconnect/login-callback', 'Auth\FranceConnectController@oauthLoginCallback');

Route::get('territoires', 'Api\TerritoireController@index');
Route::get('territoire/{slugOrId}', 'Api\TerritoireController@show');
// Route::get('territoire/{territoire}/promotedMissions', 'Api\TerritoireController@promotedMissions');
Route::get('territoire/{territoire}/cities', 'Api\TerritoireController@citiesWithAvailableMissions');

// Route::get('tags', 'Api\TagController@index');

Route::post('reseaux/lead', 'Api\ReseauController@lead');
// Route::get('reseaux/{reseau}', 'Api\ReseauController@show')->whereNumber('reseau');
// Route::get('reseaux/test', 'Api\ReseauController@test');

// Route::get('notification-temoignage/{token}', 'Api\NotificationTemoignageController@show');
// Route::get('participation/{participation}/temoignage', 'Api\TemoignageController@fromParticipation');
// Route::get('participation/{participation}/benevole-name', 'Api\ParticipationController@benevoleName');
// Route::get('participation/{participation}/mission', 'Api\ParticipationController@mission');
// Route::post('temoignage', 'Api\TemoignageController@store');

Route::group(['middleware' => ['auth:api']], function () {

    Route::get('user', 'Api\UserController@me');
    Route::get('user/structure', 'Api\UserController@structure');
    Route::get('user/roles', 'Api\UserController@roles');
    Route::get('user/actions', 'Api\ActionController@index');
    Route::get('user/actions/benevole', 'Api\ActionController@benevole');
    Route::post('user/anonymize', 'Api\UserController@anonymize');
    Route::post('user', 'Api\UserController@update');

    Route::post('profile/{profile}', 'Api\ProfileController@update');
    // Route::post('profile/{profile}/upload', 'Api\ProfileController@upload');
    // Route::delete('profile/{profile}/upload', 'Api\ProfileController@uploadDelete');
    // Route::get('profile/{profile}/participations', 'Api\ProfileController@participations');
    // Route::get('profile/{profile}/statistics', 'Api\ProfileController@statistics');

    Route::post('structure', 'Api\StructureController@store');
    Route::post('structure/{structure}', 'Api\StructureController@update');
    // Route::post('structure/{structure}/upload/{field}', 'Api\StructureController@upload');
    // Route::delete('structure/{structure}/upload/{field}', 'Api\StructureController@uploadDelete');

    // Route::post('participation', 'Api\ParticipationController@store');
    // Route::post('participation/{participation}/cancel', 'Api\ParticipationController@cancel');

    // Route::post('user/password', 'Api\UserController@updatePassword');

    // Route::get('profile/{profile?}', 'Api\ProfileController@show');

    // MESSAGES
    // Route::get('conversations', 'Api\ConversationsController@index');
    // Route::get('conversation/{conversation}', 'Api\ConversationsController@show')->where('conversation', '[0-9]+');
    // Route::get('conversations/{conversation}/messages', 'Api\ConversationsController@messages');
    // Route::post('conversations/{conversation}/messages', 'Api\MessagesController@store');
    // Route::get('conversation/{conversation}/benevole', 'Api\ConversationsController@benevole');
    // Route::post('conversation/{conversation}/setStatus', 'Api\ConversationsController@setStatus');

    // Route::post('invitation/{token}/resend', 'Api\InvitationController@resend');
    // Route::post('invitation/{token}/accept', 'Api\InvitationController@accept');
    // Route::delete('invitation/{token}/delete', 'Api\InvitationController@delete');

    Route::post('logout', 'Api\PassportController@logout');

    // API ENGAGEMENT
    // Route::get('apiengagement/mymission/{id}', 'Api\ApiEngagementController@myMission');
});

// Pour info : Les middleware 'auth:api', 'has.context.role.header' ajoutent 9 queries
Route::group(['middleware' => ['auth:api', 'has.context.role.header']], function () {
    // USERS
    // Route::post('user', 'Api\UserController@update');

    // STRUCTURES
    Route::get('structures', 'Api\StructureController@index');
    Route::get('structure/{structure}', 'Api\StructureController@show');

    // Route::delete('structure/{structure}', 'Api\StructureController@delete');
    // Route::post('structure/{structure}/restore', 'Api\StructureController@restore');

    // STRUCTURE
    // Route::post('structure/{structure}/missions', 'Api\StructureController@addMission');
    // Route::get('structure/{structure}/members', 'Api\StructureController@members');
    // Route::post('structure/{structure}/members', 'Api\StructureController@addMember');
    // Route::delete('structure/{structure}/members/{member}', 'Api\StructureController@deleteMember');
    // Route::get('structure/{structure}/actions', 'Api\ActionController@structure');
    // Route::post('structure/{structure}/reseaux', 'Api\StructureController@attachReseaux');
    // Route::delete('structure/{structure}/reseaux/{reseau}', 'Api\StructureController@detachReseau');


    // STRUCTURE INVITATIONS
    // Route::get('structure/{structure}/invitations', 'Api\StructureController@invitations');
    // Route::post('invitation', 'Api\InvitationController@store');

    // INVITATIONS
    // Route::get('invitations', 'Api\InvitationController@index');

    // MISSIONS
    Route::get('missions', 'Api\MissionController@index');
    // Route::get('mission/{mission}/benevoles', 'Api\MissionController@benevoles');
    // Route::get('mission/{mission}/responsable', 'Api\MissionController@responsable');
    Route::put('mission/{mission}', 'Api\MissionController@update');
    // Route::post('mission/{mission}/clone', 'Api\MissionController@clone');
    // Route::delete('mission/{mission}', 'Api\MissionController@delete');
    // Route::get('mission/{mission}/structure', 'Api\MissionController@structure');
    // Route::post('mission/{mission}/restore', 'Api\MissionController@restore');
    // Route::post('mission/{mission}/send-testimony-notifications', 'Api\MissionController@sendTestimonyNotifications');

    // PROFILES
    // Route::get('profiles', 'Api\ProfileController@index');

    // PARTICIPATIONS
    // Route::get('participation/{participation}', 'Api\ParticipationController@show');
    // Route::get('participation/{participation}/conversation', 'Api\ParticipationController@conversation');
    // Route::get('participation/{participation}/benevole', 'Api\ParticipationController@benevole');
    // Route::delete('participation/{participation}', 'Api\ParticipationController@delete');
    // Route::get('participations', 'Api\ParticipationController@index');
    // Route::post('participation/{participation}', 'Api\ParticipationController@update');
    // Route::post('participation/{participation}/decline', 'Api\ParticipationController@decline');
    // Route::post('participations/mass-validation', 'Api\ParticipationController@massValidation');

    // NOTIFICATIONS BENEVOLES
    // Route::get('notifications-benevoles', 'Api\NotificationBenevoleController@index');
    // Route::post('notification-benevole', 'Api\NotificationBenevoleController@store');

    // EXPORT
    // Route::get('structures/export', 'Api\StructureController@export');
    // Route::get('territoires/export', 'Api\TerritoireController@export');
    // Route::get('missions/export', 'Api\MissionController@export');
    // Route::get('profiles/export', 'Api\ProfileController@export');
    // Route::get('participations/export', 'Api\ParticipationController@export');

    // RELEASES
    // Route::get('releases', 'Api\ReleaseController@index');

    // STATISTICS
    Route::get('statistics', 'Api\StatisticsController@dashboard');
    // Route::get('statistics/missions', 'Api\StatisticsController@missions');
    // Route::get('statistics/departments', 'Api\StatisticsController@departments');
    // Route::get('statistics/places', 'Api\StatisticsController@places');
    // Route::get('statistics/occupation-rate', 'Api\StatisticsController@occupationRate');
    // Route::get('statistics/structures', 'Api\StatisticsController@structures');
    // Route::get('statistics/profiles', 'Api\StatisticsController@profiles');
    // Route::get('statistics/skills', 'Api\StatisticsController@skills');
    // Route::get('statistics/participations', 'Api\StatisticsController@participations');
    // Route::get('statistics/domaines', 'Api\StatisticsController@domaines');
    // Route::get('statistics/online', 'Api\StatisticsController@online');

    // Route::get('charts/created', 'Api\ChartController@created');

    // REMINDERS
    // Route::get('reminders', 'Api\ConfigController@reminders');

    // DOCUMENTS
    // Route::get('documents', 'Api\DocumentController@index');

    // MISSIONS TEMPLATES
    // Route::get('mission-template/{missionTemplate}', 'Api\MissionTemplateController@show');
    Route::get('mission-templates', 'Api\MissionTemplateController@index');

    // ACTIVITIES
    // Route::get('activities', 'Api\ActivityController@index');

    // TERRITOIRES
    // Route::post('territoire', 'Api\TerritoireController@store');
    Route::post('territoire/{territoire}', 'Api\TerritoireController@update');
    // Route::get('territoire/{territoire}/responsables', 'Api\TerritoireController@responsables');
    // Route::get('territoire/{territoire}/invitations', 'Api\TerritoireController@invitations');
    // Route::post('territoire/{territoire}/upload/{field}', 'Api\TerritoireController@upload');
    // Route::delete('territoire/{territoire}/upload/{field}', 'Api\TerritoireController@uploadDelete');
    // Route::delete('territoire/{territoire}/responsables/{responsable}', 'Api\TerritoireController@deleteResponsable');

    // Route::get('statistics/{type}/{id}', 'Api\StatisticsController@fetch');

    // ACTIONS
    // Route::get('actions', 'Api\ActionController@index');

    // TEMOIGNAGES
    // Route::get('temoignages', 'Api\TemoignageController@index');
    // Route::get('notifications-temoignages', 'Api\NotificationTemoignageController@index');
    // Route::get('participation/{participation}/notification-temoignage', 'Api\NotificationTemoignageController@fromParticipation');
    // Route::get('notification-temoignage/{notificationTemoignage}/resend', 'Api\NotificationTemoignageController@resend');
    // Route::get('mission/{mission}/testimonies-stats', 'Api\MissionController@testimoniesStats');

    // MISSION TEMPLATES
    // Route::post('mission-template', 'Api\MissionTemplateController@store');
    // Route::post('mission-template/{missionTemplate}', 'Api\MissionTemplateController@update');
    // Route::delete('mission-template/{missionTemplate}', 'Api\MissionTemplateController@delete');
    // Route::post('mission-template/{missionTemplate}/upload/{field}', 'Api\MissionTemplateController@upload');
    // Route::delete('mission-template/{missionTemplate}/upload/{field}', 'Api\MissionTemplateController@uploadDelete');

    // RESEAUX
    Route::get('reseaux', 'Api\ReseauController@index');
    // Route::get('structure/{structure}/reseaux', 'Api\StructureController@reseaux');

});

// ONLY ADMIN
Route::group(['middleware' => ['auth:api', 'is.admin']], function () {

    // Route::get('topito/participations', 'Api\TopitoController@participations');
    // Route::get('topito/marketplace', 'Api\TopitoController@marketplace');
    // Route::get('topito/utilisateurs-les-plus-actifs', 'Api\TopitoController@utilisateursLesPlusActifs');
    // Route::get('topito/organisations-missions', 'Api\TopitoController@organisationsMissions');
    // Route::get('topito/organisations-participations', 'Api\TopitoController@organisationsParticipations');

    // Route::post('structure/{structure}/push-api-engagement', 'Api\StructureController@pushApiEngagement');
    // Route::get('structures-without-rna', 'Api\RnaController@index');
    // Route::post('structure/{structure}/rna', 'Api\RnaController@assign');

    // // TRASH
    // Route::get('trash/{model}', 'Api\TrashController@index');
    // Route::delete('structure/{id}/destroy', 'Api\StructureController@destroy');
    // Route::delete('mission/{id}/destroy', 'Api\MissionController@destroy');
    // Route::delete('participation/{id}/destroy', 'Api\ParticipationController@destroy');

    // // THEMATIQUES
    // Route::get('thematiques', 'Api\ThematiqueController@index');
    // Route::post('thematique', 'Api\ThematiqueController@store');
    // Route::post('thematique/{thematique}', 'Api\ThematiqueController@update');
    // Route::post('thematique/{thematique}/upload', 'Api\ThematiqueController@upload');
    // Route::delete('thematique/{thematique}/upload', 'Api\ThematiqueController@uploadDelete');
    // Route::delete('thematique/{thematique}', 'Api\ThematiqueController@delete');

    // // TAGS
    // Route::get('tag/{tag}', 'Api\TagController@show');
    // Route::post('tag', 'Api\TagController@store');
    // Route::post('tag/{tag}', 'Api\TagController@update');
    // Route::delete('tag/{tag}', 'Api\TagController@delete');
    // Route::post('tag/{tag}/upload', 'Api\TagController@upload');
    // Route::delete('tag/{tag}/upload', 'Api\TagController@uploadDelete');

    // // DOCUMENTS
    // Route::get('document/{document}', 'Api\DocumentController@show');
    // Route::post('document', 'Api\DocumentController@store');
    // Route::post('document/{document}', 'Api\DocumentController@update');
    // Route::post('document/{document}/upload', 'Api\DocumentController@upload');
    // Route::delete('document/{document}/upload', 'Api\DocumentController@uploadDelete');
    // Route::delete('document/{document}', 'Api\DocumentController@delete');

    // Route::post('document/{document}/notify', 'Api\DocumentController@notify');

    // // FAQ
    // Route::post('faq', 'Api\FaqController@store');
    // Route::post('faq/{faq}', 'Api\FaqController@update');
    // Route::get('faq/{faq}', 'Api\FaqController@show');
    // Route::delete('faq/{faq}', 'Api\FaqController@delete');

    // // RELEASES
    // Route::post('release', 'Api\ReleaseController@store');
    // Route::post('release/{release}', 'Api\ReleaseController@update');
    // Route::get('release/{release}', 'Api\ReleaseController@show');
    // Route::delete('release/{release}', 'Api\ReleaseController@delete');

    // // PAGES
    // Route::get('pages', 'Api\PageController@index');
    // Route::post('page', 'Api\PageController@store');
    // Route::post('page/{page}', 'Api\PageController@update');
    // Route::delete('page/{page}', 'Api\PageController@delete');

    // // IMPERSONNATE
    // Route::post('impersonate/{user}', 'Api\UserController@impersonate');
    // Route::delete('impersonate/{token}', 'Api\UserController@stopImpersonate');

    // // TABLE EXPORT
    // Route::post('{table}/export/table', 'Api\ConfigController@export');

    // Route::get('profiles/referents/departements/export', 'Api\ProfileController@exportReferentsDepartements');
    // Route::get('profiles/referents/regions/export', 'Api\ProfileController@exportReferentsRegions');
    // Route::get('profiles/tetes-de-reseau/export', 'Api\ProfileController@exportTetesDeReseau');
    // Route::get('profiles/responsables/export', 'Api\ProfileController@exportResponsables');

    // // TERRITOIRES
    // Route::delete('territoire/{territoire}', 'Api\TerritoireController@delete');

    // // RESEAUX
    // Route::post('reseaux', 'Api\ReseauController@store');
    // Route::post('reseaux/{reseau}', 'Api\ReseauController@update');
    // Route::get('reseaux/{reseau}/responsables', 'Api\ReseauController@responsables');
    // Route::get('reseaux/{reseau}/invitations-responsables', 'Api\ReseauController@invitationsResponsables');
    // Route::post('reseaux/{reseau}/organisations', 'Api\ReseauController@attachOrganisations');
    // Route::delete('reseaux/{reseau}', 'Api\ReseauController@delete');
    // Route::delete('reseaux/{reseau}/responsables/{responsable}', 'Api\ReseauController@deleteResponsable');
    // Route::delete('reseaux/{reseau}/organisations/{organisation}', 'Api\ReseauController@detachOrganisation');

});
