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

Route::get('api/api-engagement/flux', 'Api\EngagementController@feed');

Route::get('phpmyinfo', function () {
    phpinfo(); 
})->name('phpmyinfo');

Route::group(['middleware' => ['has.api.key']], function () {
    Route::get('api/api-engagement/missions', 'Api\EngagementController@missions');
    Route::get('api/api-engagement/missions-snu-mig', 'Api\EngagementController@missionsSnuMig');
    Route::get('api/api-engagement/organisations', 'Api\EngagementController@organisations');
});
