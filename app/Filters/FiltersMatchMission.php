<?php

namespace App\Filters;

use App\Models\Mission;
use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class FiltersMatchMission implements Filter
{
    public function __invoke(Builder $query, $value, string $property): Builder
    {
        $mission = Mission::find($value);

        return $query->where(function ($query) use ($mission) {
            $query
                ->where('zip', 'LIKE', substr($mission->zip, 0, 2).'%')
                ->withAnyTags($mission->domaines)
                ->whereDoesntHave('participations', function (Builder $query) use ($mission) {
                    $query->where('mission_id', $mission->id);
                });
        });
    }
}
