<?php

namespace App\Filters;

use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class FiltersNotificationTemoignageSearch implements Filter
{
    public function __invoke(Builder $query, $value, string $property): Builder
    {
        return $query->where(function ($query) use ($value, $property) {

            $query->whereHas('participation', function (Builder $query) use ($value) {
                    $query->whereHas('profile', function (Builder $query) use ($value) {
                        $query->where('first_name', 'ILIKE', '%' . $value . '%')
                            ->orWhere('last_name', 'ILIKE', '%' . $value . '%')
                            ->orWhere('email', 'ILIKE', '%' . $value . '%')
                            ->orWhere('phone', 'ILIKE', '%' . $value . '%')
                            ->orWhere('mobile', 'ILIKE', '%' . $value . '%')
                            ->orWhere('zip', 'ILIKE', '%' . $value . '%');
                    });
            });
        });
    }
}
