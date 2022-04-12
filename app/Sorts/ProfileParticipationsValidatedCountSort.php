<?php

namespace App\Sorts;

use Spatie\QueryBuilder\Sorts\Sort;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class ProfileParticipationsValidatedCountSort implements Sort
{
    public function __invoke(Builder $query, bool $descending, string $property)
    {
        $direction = $descending ? 'DESC' : 'ASC';

        $query
            ->addSelect('profiles.*')
            ->addSelect(DB::raw('COUNT(participations.profile_id) AS participations_count'))
            ->leftJoin('participations', 'profiles.id', '=', 'participations.profile_id')
            ->where('participations.state', 'Validée')
            ->groupBy('profiles.id')
            ->orderBy('participations_count', $direction);

    }
}