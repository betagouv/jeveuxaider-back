<?php

namespace App\Filters;

use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class FiltersTagName implements Filter
{
    public function __invoke(Builder $query, $value, string $property): Builder
    {
        return $query->where(function ($query) use ($value, $property) {
            // $query->whereRaw("json_unquote(json_extract(`name`, '$.\"fr\"')) ILIKE '%" . $value . "%') or `group` LIKE '%" . $value . "%'");
            $query->whereRaw("unaccent(json_unquote(json_extract(`name`, '$.\"fr\"'))) ILIKE unaccent('%" . $value . "%') or `group` LIKE unaccent('%" . $value . "%')");
        });
    }
}
