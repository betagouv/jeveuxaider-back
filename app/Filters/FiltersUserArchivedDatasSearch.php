<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Maize\Encryptable\Encryption;
use Spatie\QueryBuilder\Filters\Filter;

class FiltersUserArchivedDatasSearch implements Filter
{
    public function __invoke(Builder $query, $value, string $property): Builder
    {
        return $query->where(function ($query) use ($value) {
            if (is_numeric($value) && strpos($value, '.') === false) {
                $query
                    ->where('user_id', $value);
            } else {
                $query->where('email', Encryption::php()->encrypt($value));
            }
        });
    }
}
