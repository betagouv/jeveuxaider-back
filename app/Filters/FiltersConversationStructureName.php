<?php

namespace App\Filters;

use App\Models\Mission;
use App\Models\Participation;
use App\Models\Structure;
use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class FiltersConversationStructureName implements Filter
{
    public function __invoke(Builder $query, $value, string $property): Builder
    {

        return $query
            ->whereHasMorph('conversable', [Participation::class], function (Builder $query) use ($value) {
                $query->whereHas('mission',  function (Builder $query) use ($value) {
                    $query->whereHas('structure',  function (Builder $query) use ($value) {
                        $query->where('name', 'ILIKE', '%'.$value.'%');
                    });
                });
            })
            ->orWhereHasMorph('conversable', [Mission::class], function (Builder $query) use ($value) {
                $query->whereHas('structure',  function (Builder $query) use ($value) {
                    $query->where('name', 'ILIKE', '%'.$value.'%');
                });
            })
            ->orWhereHasMorph('conversable', [Structure::class], function (Builder $query) use ($value) {
                $query->where('name', 'ILIKE', '%'.$value.'%');
            });
    }
}
