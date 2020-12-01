<?php

namespace App\Filters;

use App\Models\Collectivity;
use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class FiltersCollectivitiesDepartment implements Filter
{
    public function __invoke(Builder $query, $value, string $property): Builder
    {
        return $query->where(function ($query) use ($value, $property) {
            $zips = Collectivity::where('type', 'commune')
                ->get()
                ->pluck('zips')
                ->flatten()
                ->filter(function ($item) use ($value) {
                    return is_array($value) ? in_array(substr($item, 0, 2), $value) : substr($item, 0, 2) == $value;
                })
                ->toArray();

            if ($zips) {
                foreach ($zips as $zip) {
                    $query->orWhereJsonContains('zips', $zip);
                }
            } else {
                $query->where('id', -1); // Hack pour ne rien retourner
            }
        });
    }
}
