<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Mission;
use App\Models\Structure;
use Illuminate\Database\Eloquent\Builder;
use App\Services\ApiEngagement;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use App\Filters\FiltersStructureSearch;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response;

class EngagementController extends Controller
{
    public function feed()
    {
        $structuresNotInApi = [25, 7383, 5577]; // Bénénovat
        $missions = Mission::whereHas('structure', function (Builder $query) use ($structuresNotInApi) {
            $query->where('state', 'Validée')
                  ->whereNotIn('id', $structuresNotInApi);
        })->where('state', 'Validée')->where('places_left', '>', 0)->get();

        return response()->view('flux-api-engagement', compact('missions'))->header('Content-Type', 'text/xml');
    }

    public function organisations(Request $request)
    {

        $validator = Validator::make($_GET, [
            'apikey' => 'required',
            'pagination' => 'numeric|max:100',
        ]);

        if ($validator->fails()) {
            return new Response($validator->errors()->all(), 401);
        }

        $results = QueryBuilder::for(Structure::where('state', 'Validée'))
            ->allowedFilters([
                AllowedFilter::exact('department'),
                'state',
                'statut_juridique',
                AllowedFilter::custom('search', new FiltersStructureSearch),
            ])
            ->defaultSort('-id')
            ->paginate($request->input('pagination') ?? config('query-builder.results_per_page'));

        $results->getCollection()->transform(function($item, $key) {
            return [
                'id' => $item->id,
                'rna' => $item->rna,
                'api_id' => $item->api_id,
                'name' => $item->name,
                'phone' => $item->phone,
                'email' => $item->email,
                'url' => $item->full_url,
                'description' => $item->description,
                'statut_juridique' => $item->statut_juridique,
                'association_types' => $item->association_types,
                'structure_publique_type' => $item->structure_publique_type,
                'structure_publique_etat_type' => $item->structure_publique_etat_type,
                'structure_privee_type' => $item->structure_privee_type,
                'address' => [
                    'full' => $item->full_address,
                    'address' => $item->address,
                    'zip' => $item->zip,
                    'city' => $item->city,
                    'department' => $item->department,
                    'country' => $item->country,
                    'latitude' => $item->latitude,
                    'longitude' => $item->longitude,
                ],
                'website' => $item->website,
                'facebook' => $item->facebook,
                'twitter' => $item->twitter,
                'instagram' => $item->instagram,
                'donation' => $item->donation,
                'created_at' => $item->created_at,
                'publics_beneficiaires' => $item->publics_beneficiaires,
                'domaines' => $item->domaines ? $item->domaines->map(function($domaine){
                    return $domaine['name'];
                })->all() : null,
                'reseau' => $item->reseau ? [
                    'id' => $item->reseau->id,
                    'name' => $item->reseau->name,
                ] : null
            ];
        });

        return response()->json($results);
    }

    public function import()
    {
        return (new ApiEngagement())->import();
    }

    public function delete()
    {
        return (new ApiEngagement())->delete();
    }
}
