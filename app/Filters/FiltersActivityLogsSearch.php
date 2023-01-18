<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class FiltersActivityLogsSearch implements Filter
{
    public function __invoke(Builder $query, $value, string $property): Builder
    {
        return $query->where(function ($query) use ($value) {
            if (is_numeric($value) && strpos($value, '.') === false) {
                $query->where('causer_id', $value);
            } else {
                // To prevent error with comma.
                if (is_array($value)) {
                    $value = implode(',', $value);
                }
                $terms = explode(' ', $value);
                // foreach ($terms as $term) {
                //     $query->whereRaw("CONCAT(first_name, ' ', last_name, ' ', email) ILIKE ?", ['%' . $term . '%']);
                // }
                    $query->whereHas('causer.profile', function (Builder $query) use ($value) {
                        $query->where('first_name', 'ILIKE', '%'.$value.'%')
                                ->orWhere('last_name', 'ILIKE', '%'.$value.'%')
                                ->orWhere('email', 'ILIKE', '%'.$value.'%')
                                ;
                    });
             
            }
        });
    }
}
