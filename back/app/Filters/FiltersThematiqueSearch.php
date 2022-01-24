<?php

namespace App\Filters;

use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class FiltersThematiqueSearch implements Filter
{
    public function __invoke(Builder $query, $value, string $property): Builder
    {
        return $query->where(function ($query) use ($value, $property) {
            if (is_numeric($value)) {
                $query
                    ->where('id', $value);
            } else {
                $terms = explode(" ", $value);
                foreach ($terms as $term) {
                    $query->whereRaw("CONCAT(name) ILIKE ?", ['%' . $term . '%']);
                }
            }
        });
    }
}
