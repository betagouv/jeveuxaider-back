<?php

namespace App\Filters;

use App\Models\Participation;
use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class FiltersConversationParticipationTags implements Filter
{
    public function __invoke(Builder $query, $value, string $property): Builder
    {
        return $query->whereHasMorph('conversable', [Participation::class], function (Builder $query) use ($value) {
            if (is_array($value)) {
                $query->whereHas('tags', function (Builder $query) use ($value) {
                    $query->whereIn('id', $value);
                });
            } else {
                $query->whereHas('tags', function (Builder $query) use ($value) {
                    $query->where('id', $value);
                });
            }
        });
    }
}
