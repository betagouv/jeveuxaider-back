<?php

namespace App\Http\Controllers\Api;

use App\Filters\FiltersMissionIsTemplate;
use App\Filters\FiltersMissionPlacesLeft;
use App\Filters\FiltersMissionPublicsVolontaires;
use App\Filters\FiltersMissionSearch;
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
    public function missions(Request $request)
    {

        $validator = Validator::make($_GET, [
            'apikey' => 'required',
            'pagination' => 'numeric|max:100',
        ]);

        if ($validator->fails()) {
            return new Response($validator->errors()->all(), 401);
        }

        $missionsQueryBuilder = Mission::where('state', 'Validée')
            ->whereHas('structure', function (Builder $query) {
                $query->where('state', 'Validée')
                      ->whereNotIn('id', [25, 7383, 5577]);
            });

        $results = QueryBuilder::for($missionsQueryBuilder)
            ->with(['responsable','domaine', 'template', 'template.domaine', 'template.photo', 'structure', 'illustrations'])
            ->allowedFilters([
                'state',
                'type',
                AllowedFilter::exact('id'),
                AllowedFilter::exact('department'),
                AllowedFilter::exact('responsable.id'),
                AllowedFilter::exact('template.id'),
                AllowedFilter::exact('structure.id'),
                AllowedFilter::exact('structure.name'),
                AllowedFilter::exact('structure.reseaux.id'),
                AllowedFilter::exact('structure.reseaux.name'),
                AllowedFilter::exact('is_snu_mig_compatible'),
                AllowedFilter::scope('ofDomaine'),
                AllowedFilter::scope('ofTerritoire'),
                AllowedFilter::custom('place', new FiltersMissionPlacesLeft),
                AllowedFilter::custom('publics_volontaires', new FiltersMissionPublicsVolontaires),
                AllowedFilter::custom('search', new FiltersMissionSearch),
                AllowedFilter::scope('available'),
                AllowedFilter::custom('is_template', new FiltersMissionIsTemplate),
            ])
            ->defaultSort('-id')
            ->paginate($request->input('pagination') ?? config('query-builder.results_per_page'));

        $results->getCollection()->transform(function($mission, $key) {
           return $mission->format();
        });

        return response()->json($results);
    }

    public function organisation(Request $request, Structure $organisation)
    {

        $validator = Validator::make($_GET, [
            'apikey' => 'required',
        ]);

        if ($validator->fails()) {
            return new Response($validator->errors()->all(), 401);
        }

        return response()->json($organisation->format());
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
            ->with(['members', 'reseaux:id,name','domaines:id,name','overrideImage1', 'illustrations'])
            ->allowedFilters([
                AllowedFilter::exact('department'),
                'state',
                'statut_juridique',
                AllowedFilter::custom('search', new FiltersStructureSearch),
            ])
            ->defaultSort('-id')
            ->paginate($request->input('pagination') ?? config('query-builder.results_per_page'));

        $results->getCollection()->transform(function($organisation) {
            return $organisation->format();
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