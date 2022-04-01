<?php

namespace App\Sorts;

use Spatie\QueryBuilder\Sorts\Sort;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class StructureMissionsCountSort implements Sort
{
    public function __invoke(Builder $query, bool $descending, string $property)
    {
        $direction = $descending ? 'DESC' : 'ASC';

        $query
            ->addSelect('structures.*')
            ->addSelect(DB::raw('COUNT(missions.structure_id) AS missions_count'))
            ->leftJoin('missions', 'structures.id', '=', 'missions.structure_id')
            ->groupBy('structures.id')
            ->orderBy('missions_count', $direction);

    }
}