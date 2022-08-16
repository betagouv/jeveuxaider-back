<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\ValidateParticipation;
use App\Jobs\DeclineParticipation;
use Illuminate\Bus\Batch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Bus;
use Throwable;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use App\Filters\FiltersParticipationSearch;
use App\Models\Participation;

class BulkOperationController extends Controller
{
    public function participationsValidate(Request $request)
    {
        $currentUserId = Auth::guard('api')->user()->id;
        $ids = (request('isBulkAll')) ? $this->getIdsFromQuery($request, request('query')) : $request->input('ids');

        $batch = Bus::batch(
            array_map(function ($id) use ($currentUserId) {
                return new ValidateParticipation($id, $currentUserId);
            }, $ids)
        )->then(function (Batch $batch) {
            // All jobs completed successfully...
        })->catch(function (Batch $batch, Throwable $e) {
            // First batch job failure detected...
        })->finally(function (Batch $batch) {
            // The batch has finished executing...
        })->allowFailures()->dispatch();

        return $batch->id;
    }

    public function participationsDecline(Request $request)
    {
        $currentUserId = Auth::guard('api')->user()->id;
        $ids = (request('isBulkAll')) ? $this->getIdsFromQuery($request, request('query')) : $request->input('ids');

        $batch = Bus::batch(
            array_map(function ($id) use ($currentUserId, $request) {
                return new DeclineParticipation($id, $currentUserId, $request->input('reason'), $request->input('content'));
            }, $ids)
        )->then(function (Batch $batch) {
            // All jobs completed successfully...
        })->catch(function (Batch $batch, Throwable $e) {
            // First batch job failure detected...
        })->finally(function (Batch $batch) {
            // The batch has finished executing...
        })->allowFailures()->dispatch();

        return $batch->id;
    }

    private function getIdsFromQuery(Request $request, $query)
    {
        // Synchroniser avec ParticipationController@index
        return QueryBuilder::for(Participation::role($request->header('Context-Role')))
            ->allowedFilters(
                AllowedFilter::custom('search', new FiltersParticipationSearch)
                    ->default(isset($query["filter[search]"]) ? $query["filter[search]"] : []),
                AllowedFilter::exact('mission.id')
                    ->default(isset($query["filter[mission.id]"]) ? $query["filter[mission.id]"] : []),
                AllowedFilter::exact('mission.name')
                    ->default(isset($query["filter[mission.name]"]) ? $query["filter[mission.name]"] : []),
                AllowedFilter::exact('mission.department')
                    ->default(isset($query["filter[mission.department]"]) ? $query["filter[mission.department]"] : []),
                AllowedFilter::exact('mission.structure.name')
                    ->default(isset($query["filter[mission.structure.name]"]) ? $query["filter[mission.structure.name]"] : []),
                AllowedFilter::exact('mission.structure.id')
                    ->default(isset($query["filter[mission.structure.id]"]) ? $query["filter[mission.structure.id]"] : []),
                AllowedFilter::exact('mission.template.id')
                    ->default(isset($query["filter[mission.template.id]"]) ? $query["filter[mission.template.id]"] : []),
                AllowedFilter::exact('profile.id')
                    ->default(isset($query["filter[profile.id]"]) ? $query["filter[profile.id]"] : []),
                AllowedFilter::scope('ofReseau')
                    ->default(isset($query["filter[ofReseau]"]) ? $query["filter[ofReseau]"] : []),
                AllowedFilter::scope('ofTerritoire')
                    ->default(isset($query["filter[ofTerritoire]"]) ? $query["filter[ofTerritoire]"] : []),
                AllowedFilter::scope('ofActivity')
                    ->default(isset($query["filter[ofActivity]"]) ? $query["filter[ofActivity]"] : []),
                AllowedFilter::scope('ofDomaine')
                    ->default(isset($query["filter[ofDomaine]"]) ? $query["filter[ofDomaine]"] : []),
                AllowedFilter::scope('ofResponsable')
                    ->default(isset($query["filter[ofResponsable]"]) ? $query["filter[ofResponsable]"] : []),
                AllowedFilter::partial('state')
                    ->default(isset($query["filter[state]"]) ? $query["filter[state]"] : []),
                AllowedFilter::partial('mission.zip')
                    ->default(isset($query["filter[mission.zip]"]) ? $query["filter[mission.zip]"] : []),
                AllowedFilter::partial('mission.type')
                    ->default(isset($query["filter[mission.type]"]) ? $query["filter[mission.type]"] : []),
            )
            ->defaultSort('-created_at')
            ->get(['id'])->pluck('id')->all();
    }
}
