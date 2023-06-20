<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Support\Facades\Auth;

class FiltersConversationSearch implements Filter
{
    public function __invoke(Builder $query, $value, string $property): Builder
    {

        return $query->whereHas('users', function (Builder $query) use ($value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }
            $query->whereHas('profile', function (Builder $query) use ($value) {
                $query
                    ->whereRaw("concat(first_name, ' ', last_name, ' ', email) ilike '%$value%' ")
                    ->where('user_id', '!=', Auth::guard('api')->user()->id);
            });
        });
    }
}
