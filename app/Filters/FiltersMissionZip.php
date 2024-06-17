<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class FiltersMissionZip implements Filter
{
    public function __invoke(Builder $query, $value, string $property): Builder
    {
        return $query->where(function ($query) use ($value) {

            return $query->whereJsonContains('addresses', [['zip' => $value]]);
            // return $query->where(function ($query) use ($value) {
            //     if (is_array($value)) {
            //         $query->whereIn('zip', $value)
            //             ->orWhere(function ($query) use ($value) {
            //                 foreach ($value as $v) {
            //                     $query->orWhereJsonContains('autonomy_zips', [['zip' => $v]]);
            //                 }
            //             });
            //     } else {
            //         return $query->where('zip', $value)
            //             ->orWhereJsonContains('autonomy_zips', [['zip' => $value]]);
            //     }
            // });
        });
    }
}
