<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class FiltersTemoignageSearch implements Filter
{
    public function __invoke(Builder $query, $value, string $property): Builder
    {
        return $query->where(function ($query) use ($value) {
            $query->where('testimony', 'ILIKE', '%'.$value.'%')
                ->orWhereHas('participation', function (Builder $query) use ($value) {
                    $query->whereHas('profile', function (Builder $query) use ($value) {
                        $query->where('first_name', 'ILIKE', '%'.$value.'%')
                            ->orWhere('last_name', 'ILIKE', '%'.$value.'%')
                            ->orWhere('email', 'ILIKE', '%'.$value.'%')
                            ->orWhere('phone', 'ILIKE', '%'.$value.'%')
                            ->orWhere('mobile', 'ILIKE', '%'.$value.'%')
                            ->orWhere('zip', 'ILIKE', '%'.$value.'%');
                    });
                    if (is_numeric($value)) {
                        $query->orWhere('mission_id', $value);
                    }
                });
        });
    }
}
