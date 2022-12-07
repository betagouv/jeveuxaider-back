<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class FiltersParticipationSearch implements Filter
{
    public function __invoke(Builder $query, $value, string $property): Builder
    {
        return $query->where(function ($query) use ($value) {
            if (is_numeric($value) && strpos($value, '.') === false) {
                $query->where('id', $value);
            } else {
                // To prevent error with comma.
                if (is_array($value)) {
                    $value = implode(',', $value);
                }
                $terms = explode(' ', $value);
                $query->whereHas('profile', function (Builder $query) use ($terms) {
                    foreach ($terms as $term) {
                        $query->whereRaw("CONCAT(first_name, ' ', last_name, ' ', email) ILIKE ?", ['%' . $term . '%']);
                    }
                });
            }
        });
    }
}
