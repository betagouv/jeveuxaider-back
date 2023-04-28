<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Rule extends Model
{
    use LogsActivity;

    protected $table = 'rules';

    protected $guarded = [
        'id',
    ];

    protected $casts = [
        'events' => 'array',
        'conditions' => 'json',
        'actions' => 'json',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*'])
            ->logExcept(['updated_at', 'last_triggered_at', 'triggers_count'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

}
