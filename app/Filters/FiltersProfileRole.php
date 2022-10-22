<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class FiltersProfileRole implements Filter
{
    public function __invoke(Builder $query, $value, string $property): Builder
    {
        switch ($value) {
            case 'admin':
                return $query->whereHas('user', function (Builder $query) {
                    $query->role('admin');
                });
                break;
            case 'analyste':
                return $query->where('is_analyste', true);
                break;
            case 'referent':
                return $query->whereNotNull('referent_department');
                break;
            case 'referent_regional':
                return $query->whereNotNull('referent_region');
                break;
            case 'tete_de_reseau':
                return $query->whereNotNull('tete_de_reseau_id');
                break;
            case 'responsable':
                return $query->whereHas('structures');
                break;
            case 'responsable_territoire':
                return $query->whereHas('territoires');
                break;
            default:
                return $query;
                break;
        }
    }
}
