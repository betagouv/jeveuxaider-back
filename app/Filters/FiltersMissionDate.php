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
            return $query->where('start_date', '>', Carbon::now()->startOfDay());
        }
        if ($value == 'in_progress') {
            return $query->where('start_date', '<=', Carbon::now()->startOfDay())
                ->where(function (Builder $query) {
                    $query->where('end_date', '>=', Carbon::now()->startOfDay())->orWhereNull('end_date');
                });
        }
        if ($value == 'over') {
            return $query->where('end_date', '<', Carbon::now()->startOfDay());
        }
    }
}
