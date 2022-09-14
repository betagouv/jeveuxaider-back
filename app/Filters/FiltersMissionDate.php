<?php

namespace App\Filters;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class FiltersMissionDate implements Filter
{
    public function __invoke(Builder $query, $value, string $property): Builder
    {
        if ($value == 'incoming') {
            return $query->where('start_date', '>', Carbon::now());
        }
        if ($value == 'in_progress') {
            return $query->where('start_date', '<', Carbon::now())
                ->where(function (Builder $query) {
                    $query->where('end_date', '>', Carbon::now())->orWhereNull('end_date');
                });
        }
        if ($value == 'over') {
            return $query->where('end_date', '<', Carbon::now());
        }
    }
}
