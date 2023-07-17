<?php

namespace App\Filters;

use App\Models\Participation;
use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Support\Facades\Auth;

class FiltersConversationSearch implements Filter
{
    public function __invoke(Builder $query, $value, string $property): Builder
    {

        return $query
            // ->whereHas('messages', function (Builder $query) use ($value) {
            //     $query->where('content', 'ilike', '%'.$value.'%');
            // })
            // ->whereHasMorph('conversable', [Participation::class], function (Builder $query) use ($value) {
            //     $query
            //         ->whereHas('mission', function (Builder $query) use ($value) {
            //             $query
            //                 ->where('zip', $value)
            //                 ->orWhere('city', $value);
            //         })
            //     ;
            // })
            ->where(function (Builder $query) use ($value){
                $query->whereHas('users', function (Builder $query) use ($value) {
                    if (is_array($value)) {
                        $value = implode(',', $value);
                    }
                    $query->whereHas('profile', function (Builder $query) use ($value) {
                        $query
                            ->whereRaw("CONCAT(first_name, ' ', last_name, ' ', email) ILIKE ?", ['%' . $value . '%'])
                            ->where('user_id', '!=', Auth::guard('api')->user()->id);
                    });
                });
            });
    }
}
