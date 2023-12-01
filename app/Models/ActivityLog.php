<?php

namespace App\Models;

use Spatie\Activitylog\Models\Activity as SpatieActivity;
use Illuminate\Database\Eloquent\Builder;

class ActivityLog extends SpatieActivity
{
    protected $casts = [
        'properties' => 'collection',
        'data' => 'array',
    ];

    protected static function booted()
    {
        static::addGlobalScope('exclude_not_used', function (Builder $builder) {
            $builder->where(function (Builder $query) {
                $query
                    ->whereNotIn('subject_type', [
                        'App\Models\Collectivity',
                    ])
                    ->orWhereNull('subject_type');
            });
        });
    }

}
