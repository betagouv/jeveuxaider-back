<?php

namespace App\Filters;

use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class FiltersConversationStatus implements Filter
{
    public function __invoke(Builder $query, $value, string $property) : Builder
    {
        return $query->whereHas('users', function (Builder $subquery) use ($value) {
            $subquery->where('conversations_users.user_id', Auth::guard('api')->user()->id);
            $subquery->where('conversations_users.status', $value);
        });
    }
}
