<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
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
            case 'admin':
                return $query;
                break;
            case 'responsable':
                $user = Auth::guard('api')->user();

                return $query
                    ->whereHas('mission', function (Builder $query) use ($user) {
                        if ($user->context_role == 'responsable' && $user->contextable_type == 'structure' && ! empty($user->contextable_id)) {
                            $query->where('structure_id', $user->contextable_id);
                        } else {
                            $query->where('structure_id', $user->structures->pluck('id')->first());
                        }
                    });
            break;
            default:
                abort(403, 'This action is not authorized');
                break;
        }
    }
}
