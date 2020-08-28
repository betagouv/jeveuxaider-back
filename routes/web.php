<?php

use Illuminate\Support\Facades\Http;


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

Route::get('/test-api', function () {

    $response = Http::withHeaders([
        'apikey' => config('app.api_engagement_key'),
    ])->get('http://api.jobsmail.tech/v0/mission');

    // Format for Algolia
    if($response->successful()) {
        $missions = array_map(fn($mission) => [
            'objectID' => 'ApiEngagement/' . $mission['_id'],
            'publisher_name' => $mission['publisherName'],
            'application_url' => $mission['applicationUrl'],
            'id' => $mission['_id'],
            'name' => $mission['title'],
            'city' => $mission['city'] ?? 'Ville',
            'department' => $mission['department'] ?? null,
            'department_name' => $mission['department'] ?? null,
            'zip' => $mission['postalCode'] ?? null,
            'places_left' => $mission['places'],
            'participations_max' => $mission['places'],
            'structure' => [
                'id' => $mission['organizationId'],
                'name' => $mission['organizationName'],
            ],
            // 'type' => $this->type, ( Mission en présentiel / Mission à distance )
            'template_title' => 'Titre du template',
            'domaine_name' => 'Protection de la nature',
            'domaine_image' => 'https://reserve-civique-prod.osu.eu-west-2.outscale.com/public/production/154/FFR5Cx5qbSjCBy0.svg', // Url de l'icone du domaine
            'domaines' => ['Environnement'],
        ], $response['data']);

        // Send to Algolia
        $client = Algolia\AlgoliaSearch\SearchClient::create(
            config('scout.algolia.id'),
            config('scout.algolia.secret')
          );

        $index = $client->initIndex(config('scout.prefix').'_covid_missions');

        $index->saveObjects($missions);
    }

    return $missions;
    // return $response->json();
});

Route::get('/{any}', 'PagesController@spa')->where('any', '.*');

