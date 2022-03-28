<?php

namespace App\Filters;

use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class FiltersParticipationSearch implements Filter
{
    public function __invoke(Builder $query, $value, string $property): Builder
    {
        return $query->where(function ($query) use ($value) {
            if (is_numeric($value)) {
                $query->where('id', $value);
            } else {
                if (is_array($value)) {
                    $value = implode(',', $value);
                }
                $terms = explode(" ", $value);
                $query->whereHas('profile', function (Builder $query) use ($terms) {
                    foreach ($terms as $term) {
                        $query->whereRaw("CONCAT(first_name, ' ', last_name, ' ', email) ILIKE ?", ['%' . $term . '%']);
                    }
                });
            }
        });
    }
}
