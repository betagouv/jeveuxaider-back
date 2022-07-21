<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class FiltersStructureWithRna implements Filter
{
    public function __invoke(Builder $query, $value, string $property): Builder
    {
        return $query->where(function ($query) use ($value) {
            if ($value == 'empty') {
                $query->whereNull('rna');
            } elseif ($value == 'na') {
                $query->where('rna', 'N/A');
            } elseif ($value == 'filled') {
                $query->whereNotNull('rna')->where('rna', '!=', 'N/A');
            }
        });
    }
}
