<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class NotificationBenevole extends Model
{
    protected $table = 'notifications_benevoles';

    protected $fillable = [
        'mission_id',
        'profile_id',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function mission()
    {
        return $this->belongsTo('App\Models\Mission');
    }

    public function profile()
    {
        return $this->belongsTo('App\Models\Profile');
    }

    public function scopeRole($query, $contextRole)
    {
        switch ($contextRole) {
            case 'responsable':
                return $query
                    ->whereHas('mission', function (Builder $query) {
                        $query->where('structure_id', Auth::guard('api')->user()->profile->structures->pluck('id')->first());
                    });
            break;
        }
    }
}
