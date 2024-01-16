<?php

namespace App\Http\Controllers\Api;

use App\Filters\FiltersMissionDate;
use App\Filters\FiltersMissionIsTemplate;
use App\Filters\FiltersMissionPlacesLeft;
use App\Filters\FiltersMissionPublicsVolontaires;
use App\Filters\FiltersMissionSearch;
use App\Filters\FiltersStructureSearch;
use App\Http\Controllers\Controller;
use App\Models\Mission;
use App\Models\Structure;
use App\Services\ApiEngagement;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

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
            ->where('is_online', true)
            ->whereHas('structure', function (Builder $query) {
                $query->where('state', 'Validée')
                      ->whereNotIn('id', [25, 7383, 5577]);
            });

        $results = QueryBuilder::for($missionsQueryBuilder)
            ->with(['responsable', 'domaine', 'activity', 'template', 'template.activity', 'template.domaine', 'template.photo', 'structure', 'structure.reseaux', 'illustrations'])
            ->allowedFilters([
                'state',
                'type',
                AllowedFilter::exact('id'),
                AllowedFilter::exact('department'),
                AllowedFilter::exact('responsable.id'),
                AllowedFilter::exact('template.id'),
                AllowedFilter::exact('structure.id'),
                AllowedFilter::exact('structure.name'),
                'structure.statut_juridique',
                AllowedFilter::exact('structure.reseaux.id'),
                AllowedFilter::exact('structure.reseaux.name'),
                AllowedFilter::exact('is_snu_mig_compatible'),
                AllowedFilter::scope('ofDomaine'),
                AllowedFilter::scope('ofTerritoire'),
                AllowedFilter::scope('ofActivity'),
                AllowedFilter::scope('hasActivity'),
                AllowedFilter::scope('hasTemplate'),
                AllowedFilter::custom('place', new FiltersMissionPlacesLeft()),
                AllowedFilter::custom('date', new FiltersMissionDate()),
                AllowedFilter::custom('publics_volontaires', new FiltersMissionPublicsVolontaires()),
                AllowedFilter::custom('search', new FiltersMissionSearch()),
                AllowedFilter::scope('available'),
                AllowedFilter::custom('is_template', new FiltersMissionIsTemplate()),
                AllowedFilter::exact('is_autonomy'),
            ])
            ->defaultSort('-id')
            ->paginate($request->input('pagination') ?? config('query-builder.results_per_page'));

        $results->getCollection()->transform(function ($mission, $key) {
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
            ->with(['members', 'reseaux:id,name', 'domaines:id,name', 'overrideImage1', 'illustrations'])
            ->allowedFilters([
                AllowedFilter::exact('department'),
                'state',
                'statut_juridique',
                AllowedFilter::custom('search', new FiltersStructureSearch()),
            ])
            ->defaultSort('-id')
            ->paginate($request->input('pagination') ?? config('query-builder.results_per_page'));

        $results->getCollection()->transform(function ($organisation) {
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
