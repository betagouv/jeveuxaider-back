<?php

namespace App\Sorts;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\Sorts\Sort;

class StructurePlacesLeftSort implements Sort
{
    public function __invoke(Builder $query, bool $descending, string $property)
    {
        $direction = $descending ? 'DESC' : 'ASC';

        $query
            ->addSelect('structures.*')
            ->addSelect(DB::raw('SUM(missions.places_left) AS places_left'))
            ->join('missions', 'structures.id', '=', 'missions.structure_id')
            ->whereNull('structures.deleted_at')
            ->whereNull('missions.deleted_at')
            ->where('structures.state', 'Validée')
            ->where('missions.state', 'Validée')
            ->where('missions.is_registration_open', true)
            ->groupBy('structures.id')
            ->orderBy('places_left', $direction);
    }
}
