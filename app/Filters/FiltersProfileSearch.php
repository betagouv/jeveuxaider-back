<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class FiltersProfileSearch implements Filter
{
    public function __invoke(Builder $query, $value, string $property): Builder
    {
        return $query->where(function ($query) use ($value) {
            if (is_numeric($value)) {
                $query
                    ->where('id', $value);
            } else {
                $terms = explode(' ', $value);
                foreach ($terms as $term) {
                    $query->whereRaw("CONCAT(first_name, ' ', last_name, ' ', email) ILIKE ?", ['%'.$term.'%']);
                }
            }
        });
    }
}
