<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class FiltersParticipationMissionZip implements Filter
{
    public function __invoke(Builder $query, $value, string $property): Builder
    {
        return $query->where(function ($query) use ($value) {
            $query->whereHas('mission', function (Builder $query) use ($value) {
                $query
                    ->where('type', 'Mission en présentiel')
                    ->where(function ($query) use ($value) {
                        if(is_array($value)) {
                            foreach ($value as $v) {
                                $query->orWhereJsonContains('addresses', [['zip' => $v]]);
                            }
                        } else {
                            $query->whereJsonContains('addresses', [['zip' => $value]]);
                        }
                    });
            });
        });
    }
}
