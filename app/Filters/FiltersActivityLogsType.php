<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class FiltersActivityLogsType implements Filter
{
    public function __invoke(Builder $query, $value, string $property): Builder
    {
        return $query->where(function ($query) use ($value) {
            switch($value){
                case 'organisations':
                    $query->where('subject_type', 'App\Models\Structure');
                    break;
                case 'missions':
                    $query->where('subject_type', 'App\Models\Mission');
                    break;
                case 'participations':
                    $query->where('subject_type', 'App\Models\Participation');
                    break;
                case 'utilisateurs':
                    $query->where('subject_type', 'App\Models\Profile');
                    break;
                case 'rules':
                    $query->where('subject_type', 'App\Models\Rule');
                    break;
                case 'tags':
                    $query->where('subject_type', 'App\Models\StructureTag');
                    break;
            }
        });
    }
}
