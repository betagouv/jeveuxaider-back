<?php

namespace App\Http\Controllers\Api;

use App\Exports\CollectivitiesExport;
use App\Exports\DepartmentsExport;
use App\Exports\DomainesExport;
use App\Filters\FiltersCollectivitySearch;
use App\Filters\FiltersTagName;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Collectivity;
use App\Models\Mission;
use App\Models\Participation;
use App\Models\Profile;
use App\Models\Structure;
use App\Models\Tag;
use App\Models\Territoire;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class TopitoController extends Controller
{
    public function benevolesDuMoment(Request $request)
    {
        $results = DB::select("
            SELECT participations.profile_id, COUNT(*) AS count FROM participations
            WHERE participations.deleted_at IS NULL
            GROUP BY participations.profile_id
            ORDER BY count
            DESC LIMIT 5
        ");

        $ids = collect($results)->pluck('profile_id');
        $items = Profile::whereIn('id', $ids)->get();
        $items->map(function ($item) use ($results) {
            $item->count = collect($results)->filter(function ($values) use ($item) {
                return $values->profile_id == $item->id;
            })->first()->count;
            return $item;
        });

        $sortedItems = $items->sortByDesc('count');

        return [
            'items' => $sortedItems->values()->all()
        ];
    }

    public function utilisateursLesPlusActifs(Request $request)
    {

        $results = DB::select("
            SELECT activity_log.causer_id, COUNT(*) AS count FROM activity_log
            WHERE activity_log.causer_type = 'App\Models\User'
            GROUP BY activity_log.causer_id
            ORDER BY count
            DESC LIMIT 5
        ");

        $ids = collect($results)->pluck('causer_id');
        $items = User::whereIn('id', $ids)->get();
        $items->map(function ($item) use ($results) {
            $item->count = collect($results)->filter(function ($values) use ($item) {
                return $values->causer_id == $item->id;
            })->first()->count;
            return $item;
        });

        $sortedItems = $items->sortByDesc('count');

        return [
            'items' => $sortedItems->values()->all()
        ];
    }

    public function organisationsMissions(Request $request)
    {

        $results = DB::select("
            SELECT missions.structure_id, COUNT(*) AS count FROM missions
            WHERE missions.deleted_at IS NULL
            GROUP BY missions.structure_id
            ORDER BY count
            DESC LIMIT 5
        ");

        $ids = collect($results)->pluck('structure_id');
        $items = Structure::whereIn('id', $ids)->get();
        $items->map(function ($item) use ($results) {
            $item->count = collect($results)->filter(function ($values) use ($item) {
                return $values->structure_id == $item->id;
            })->first()->count;
            return $item;
        });

        $sortedItems = $items->sortByDesc('count');

        return [
            'items' => $sortedItems->values()->all()
        ];
    }

    public function organisationsParticipations(Request $request)
    {
        $results = DB::select("
            SELECT COUNT(*) AS count, missions.structure_id FROM participations
            LEFT JOIN missions ON participations.mission_id = missions.id
            LEFT JOIN structures ON missions.structure_id = structures.id
            WHERE participations.deleted_at IS NULL
            AND missions.deleted_at IS NULL
            GROUP BY missions.structure_id
            ORDER BY count
            DESC LIMIT 5
        ");

        $ids = collect($results)->pluck('structure_id');
        $items = Structure::whereIn('id', $ids)->get();
        $items->map(function ($item) use ($results) {
            $item->count = collect($results)->filter(function ($values) use ($item) {
                return $values->structure_id == $item->id;
            })->first()->count;
            return $item;
        });

        $sortedItems = $items->sortByDesc('count');

        return [
            'items' => $sortedItems->values()->all()
        ];
    }
}
