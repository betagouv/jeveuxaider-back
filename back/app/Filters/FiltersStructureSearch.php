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
                $query
                ->where('zip', 'ILIKE', '%' . $value . '%')
                ->orWhere('id', $value);
            } else {
                $terms = explode(" ", $value);
                foreach ($terms as $term) {
                    $query->whereRaw("CONCAT(name, ' ', city, ' ', rna) ILIKE ?", ['%' . $term . '%']);
                }
            }
        });
    }
}
