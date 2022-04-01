<?php

namespace App\Filters;

use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class FiltersStructureSearch implements Filter
{
    public function __invoke(Builder $query, $value, string $property): Builder
    {
        return $query->where(function ($query) use ($value) {
            if (is_numeric($value)) {
                $query->where('structures.id', $value);
            } else {
                if (is_array($value)) {
                    $value = implode(',', $value);
                }
                $terms = explode(" ", $value);
                foreach ($terms as $term) {
                    $query->whereRaw("CONCAT(structures.name, ' ', structures.city, ' ', structures.rna) ILIKE ?", ['%' . $term . '%']);
                }
            }
        });
    }
}
