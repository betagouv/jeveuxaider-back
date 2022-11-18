<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class FiltersReseauSearch implements Filter
{
    public function __invoke(Builder $query, $value, string $property): Builder
    {
        return $query->where(function ($query) use ($value) {
            if (is_numeric($value) && strpos($value, '.') === false) {
                $query->where('id', $value);
            } else {
                if (is_array($value)) {
                    $value = implode(',', $value);
                }
                $terms = explode(' ', $value);
                foreach ($terms as $term) {
                    $query->whereRaw("CONCAT(name, ' ') ILIKE ?", ['%'.$term.'%']);
                }
            }
        });
    }
}
