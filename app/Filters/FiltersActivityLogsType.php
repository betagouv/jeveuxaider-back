<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class FiltersActivityLogsType implements Filter
{
    public function __invoke(Builder $query, $value, string $property): Builder
    {
        return $query->where(function ($query) use ($value) {
            if (is_array($value)) {
                foreach ($value as $v) {
                    $this->setCondition($v, $query);
                }
            } else {
                $this->setCondition($value, $query);
            }
        });
    }

    private function setCondition($value, $query)
    {
        switch($value) {
            case 'organisations':
                $query->orWhere('subject_type', 'App\Models\Structure');
                break;
            case 'missions':
                $query->orWhere('subject_type', 'App\Models\Mission');
                break;
            case 'participations':
                $query->orWhere('subject_type', 'App\Models\Participation');
                break;
            case 'utilisateurs':
                $query->orWhere('subject_type', 'App\Models\Profile');
                break;
            case 'rules':
                $query->orWhere('subject_type', 'App\Models\Rule');
                break;
            case 'tags':
                $query->orWhere('subject_type', 'App\Models\StructureTag');
                break;
        }
    }
}
