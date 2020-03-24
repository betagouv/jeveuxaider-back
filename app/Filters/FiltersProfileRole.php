<?php

namespace App\Filters;

use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class FiltersProfileRole implements Filter
{
    public function __invoke(Builder $query, $value, string $property) : Builder
    {
        switch ($value) {
            case 'admin':
                return $query->whereHas('user', function (Builder $query) use ($value) {
                    $query->where('is_admin', true);
                });
            break;
            case 'volontaire':
                return $query->whereHas('user', function (Builder $query) use ($value) {
                    $query->where('context_role', 'volontaire');
                });
            break;
            case 'referent':
                return $query->whereHas('user', function (Builder $query) use ($value) {
                    $query->whereNotNull('referent_department');
                });
            break;
            case 'superviseur':
                return $query->whereHas('user', function (Builder $query) use ($value) {
                    $query->whereNotNull('reseau_id');
                });
            break;
            case 'responsable':
                return $query->whereHas('structures', function (Builder $query) use ($value) {
                    $query->where('role', 'responsable');
                });
            break;
            case 'tuteur':
                return $query->whereHas('structures', function (Builder $query) use ($value) {
                    $query->where('role', 'tuteur');
                });
            break;
            default:
                return $query;
            break;
        }
    }
}
