<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class MissionUserWaitingList extends Model
{
    use LogsActivity;
    use HasFactory;
    use SoftDeletes;

    protected $table = 'missions_users_waiting_list';

    protected $guarded = [
        'id',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function mission()
    {
        return $this->belongsTo('App\Models\Mission');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function scopeOfUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
}
