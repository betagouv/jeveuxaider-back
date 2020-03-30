<?php

use App\Models\Mission;
use Spatie\QueryBuilder\QueryBuilder;
use App\Filters\FiltersMissionCeu;
use Spatie\QueryBuilder\AllowedFilter;
use App\Filters\FiltersMissionSearch;
use App\Filters\FiltersMissionLieu;
use App\Filters\FiltersMissionPlacesLeft;
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
// Route::get('/', 'PagesController@home');
// Route::get('/a-propos', 'PagesController@about');
// Route::get('/confidentialite', 'PagesController@confidentiality');
// Route::get('/centre-d-aide', 'PagesController@help');
// Route::get('/regles-de-securite', 'PagesController@securityRules');


Route::get('/debug', function () {


    $mission = Mission::find(7);
    return $mission;
    $query = Mission::role('admin')->withCountParticipationsActive();

    return QueryBuilder::for($query)
    ->allowedFilters([
        'name',
        'state',
        'format',
        'type',
        'department',
        AllowedFilter::custom('ceu', new FiltersMissionCeu),
        AllowedFilter::custom('search', new FiltersMissionSearch),
        AllowedFilter::custom('lieu', new FiltersMissionLieu),
        AllowedFilter::custom('place', new FiltersMissionPlacesLeft),
    ])
    ->defaultSort('-updated_at')
    ->paginate(config('query-builder.results_per_page'));
});

// SPA VUE
Route::get('/{any}', 'PagesController@spa')->where('any', '.*');
