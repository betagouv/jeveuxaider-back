<?php

namespace App\Filters;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Support\Facades\Auth;

class FiltersParticipationNeedToBeTreated implements Filter
{
    public function __invoke(Builder $query, $value, string $property): Builder
    {
        return $query->where(function ($query) use ($value) {
            if ($value === true) {
                $currentUser = User::find(Auth::guard('api')->user()->id);
                $query->needToBeTreated()->ofResponsable($currentUser->profile->id);
            }
        });
    }
}
