<?php

namespace App\Filters;

use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class FiltersStructureWithRna implements Filter
{
    public function __invoke(Builder $query, $value, string $property): Builder
    {
        return $query->where(function ($query) use ($value, $property) {
            if($value == 'empty'){
                $query->whereNull('rna');
            } else if($value == 'na') {
                $query->where('rna','N/A');
            } else if($value=='filled') {
                $query->whereNotNull('rna')->where('rna', '!=', 'N/A');
            }
        });
    }
}
