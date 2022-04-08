<?php

namespace App\Models;

use Spatie\Activitylog\Models\Activity as SpatieActivity;

class ActivityLog extends SpatieActivity
{
    protected $casts = [
        'properties' => 'collection',
        'data' => 'array',
    ];
}
