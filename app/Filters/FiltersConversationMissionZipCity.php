<?php

namespace App\Filters;

use App\Models\Mission;
use App\Models\Participation;
use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class FiltersConversationMissionZipCity implements Filter
{
    public function __invoke(Builder $query, $value, string $property): Builder
    {

        return $query
            ->whereHasMorph('conversable', [Participation::class], function (Builder $query) use ($value) {
                $query->whereHas('mission', function (Builder $query) use ($value) {
                    // $query
                    //     ->where('zip','ILIKE', '%'.$value.'%')
                    //     ->orWhere('city', 'ILIKE', '%'.$value.'%');
                    $query
                        ->whereJsonContains('addresses', [['zip' => $value]])
                        ->orWhereJsonContains('addresses', [['city' => $value]]);
                });
            })
            ->orWhereHasMorph('conversable', [Mission::class], function (Builder $query) use ($value) {
                $query
                    ->whereJsonContains('addresses', [['zip' => $value]])
                    ->orWhereJsonContains('addresses', [['city' => $value]]);
            });
    }
}
