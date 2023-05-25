<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class FiltersParticipationBenevoleSearch implements Filter
{
    public function __invoke(Builder $query, $value, string $property): Builder
    {
        return $query->where(function ($query) use ($value) {

            if (is_array($value)) {
                $value = implode(',', $value);
            }
            $terms = explode(' ', $value);

            $query
                ->whereHas('mission.responsable', function (Builder $query) use ($terms) {
                    foreach ($terms as $term) {
                        $query->whereRaw("CONCAT(first_name, ' ', last_name, ' ', email) ILIKE ?", ['%' . $term . '%']);
                    }
                })
                ->orWhereHas('mission', function (Builder $query) use ($value) {
                    $query->where('name', 'ILIKE', '%'.$value.'%')
                        ->orWhereHas('template', function (Builder $query) use ($value) {
                            $query->where('title', 'ILIKE', '%'.$value.'%');
                        })
                        ->orWhereHas('structure', function (Builder $query) use ($value) {
                            $query->where('name', 'ILIKE', '%'.$value.'%');
                        });
                });
        });
    }
}
