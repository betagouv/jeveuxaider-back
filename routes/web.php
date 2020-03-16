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

/* TODO : Remove route closure for cache
Route::get('/', function () {
    return view('welcome');
});
*/
/*

Route::get('/debug', function () {

});
*/


/*
    select
    sum(youngs_count) as total_youngs,
    sum(participations_max) as total_participations_max
    from(
        SELECT
            missions.participations_max,
            COUNT(youngs.id) youngs_count
        FROM
            missions
            LEFT JOIN youngs ON youngs.mission_id = missions.id
        GROUP BY
            missions.id
    ) youngs
*/
