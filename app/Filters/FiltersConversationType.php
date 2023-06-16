<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Spatie\QueryBuilder\Filters\Filter;
use Carbon\Carbon;

class FiltersConversationType implements Filter
{
    public function __invoke(Builder $query, $value, string $property): Builder
    {

        if($value == 'all'){
            return $query
                ->whereHas('users', function (Builder $query) {
                    $query
                        ->where('conversations_users.user_id', Auth::guard('api')->user()->id)
                        ->where('conversations_users.status', true);
                });
            return $query;
        }

        if($value == 'unread'){
            return $query
                ->whereHas('messages', function (Builder $query) {
                    $query->where('from_id', '!=',  Auth::guard('api')->user()->id);
                })
                ->whereHas('users', function (Builder $query) {
                    $query
                        ->where(function($query){
                            $query->whereRaw('conversations_users.read_at < conversations.updated_at')
                                ->orWhere('conversations_users.read_at', null);
                        })
                        ->where('conversations_users.status', true);
                });
        }

        if($value == 'archived'){
            return $query
                ->whereHas('users', function (Builder $query) {
                    $query
                        ->where('conversations_users.user_id', Auth::guard('api')->user()->id)
                        ->where('conversations_users.status', false);
                });
        }

        if($value == 'participations_to_be_treated'){
            return $query
                ->where('conversable_type', 'App\Models\Participation')
                ->whereHas('conversable', function (Builder $query) {
                    $query->where(function($query){
                        $query->where('participations.state', 'En attente de validation')
                            ->where('participations.created_at', '<', Carbon::now()->subDays(7)->startOfDay());
                    })
                    ->orWhere(function($query){
                        $query
                            ->where('participations.state', 'En cours de traitement')
                            ->where('participations.created_at', '<', Carbon::now()->subMonths(2)->startOfDay());
                    });
                });
        }

    }
}
