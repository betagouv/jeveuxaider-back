<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Support\Str;

class Participation extends Model
{
    use LogsActivity;

    protected $table = 'participations';

    protected $attributes = [
        'state' => 'En attente de validation',
    ];

    protected $fillable = [
        'mission_id',
        'profile_id',
        'state',
    ];

    const ACTIVE_STATUS = [
        'En attente de validation',
        'En cours de traitement',
        'Validée',
    ];

    protected static $logFillable = true;

    protected static $logOnlyDirty = true;

    protected static $submitEmptyLogs = false;

    public function mission()
    {
        return $this->belongsTo('App\Models\Mission');
    }

    public function profile()
    {
        return $this->belongsTo('App\Models\Profile');
    }

    public function conversation()
    {
        return $this->morphOne('App\Models\Conversation', 'conversable');
    }

    public function scopeRole($query, $contextRole)
    {
        switch ($contextRole) {
            case 'admin':
            case 'analyste':
                return $query;
                break;
            case 'referent':
                return $query
                    ->whereHas('mission', function (Builder $query) {
                        $query->where('department', Auth::guard('api')->user()->profile->referent_department);
                    });
                break;
            case 'referent_regional':
                return $query
                    ->whereHas('mission', function (Builder $query) {
                        $query->whereIn('department', config('taxonomies.regions.departments')[Auth::guard('api')->user()->profile->referent_region]);
                    });
                break;
            case 'tete_de_reseau':
                return $query->ofReseau(Auth::guard('api')->user()->profile->teteDeReseau->id);
                break;
            case 'responsable':
                $user = Auth::guard('api')->user();
                return $query
                    ->whereHas('mission', function (Builder $query) use ($user) {
                        if ($user->context_role == 'responsable' && $user->contextable_type == 'structure' && !empty($user->contextable_id)) {
                            $query->where('structure_id', $user->contextable_id);
                        } else {
                            $query->where('structure_id', $user->profile->structures->pluck('id')->first());
                        }
                    });
                break;
        }
    }

    public function scopeOfReseau($query, $reseau_id)
    {
        return $query->whereHas('mission', function (Builder $query) use ($reseau_id) {
            $query->ofReseau($reseau_id);
        });
    }

    public function scopeDepartment($query, $value)
    {
        return $query
            ->whereHas('mission', function (Builder $query) use ($value) {
                $query->where('department', $value);
            });
    }

    public function scopeStructure($query, $value)
    {
        return $query
            ->whereHas('mission', function (Builder $query) use ($value) {
                $query->whereHas('structure', function (Builder $query) use ($value) {
                    $query->where('id', $value);
                });
            });
    }

    public function scopeDomaine($query, $domain_id)
    {
        return $query
            ->whereHas('mission', function (Builder $query) use ($domain_id) {
                $query->domaine($domain_id);
            });
    }

    public function scopeOfTerritoire($query, $territoire_id)
    {
        return $query->whereHas('mission', function (Builder $query) use ($territoire_id) {
            $query->ofTerritoire($territoire_id);
        });
    }

    public function deleteQuietly(array $options = [])
    {
        return static::withoutEvents(function () use ($options) {
            return $this->delete($options);
        });
    }

    public function temoignage()
    {
        return $this->hasOne('App\Models\Temoignage', 'participation_id');
    }

    public function notificationTemoignage()
    {
        return $this->hasOne('App\Models\NotificationTemoignage', 'participation_id');
    }

    public function scopeState($query, $state)
    {
        return $query->where('state', $state);
    }

    public function sendNotificationTemoignage()
    {
        // Skip if not Validée.
        if ($this->state != 'Validée') {
            return;
        }

        // Skip if notification already exists.
        if (NotificationTemoignage::where('participation_id', $this->id)->exists()) {
            return;
        }

        do {
            $token = Str::random(32);
        } while (NotificationTemoignage::where('token', $token)->first());

        NotificationTemoignage::create([
            'token' => $token,
            'participation_id' => $this->id,
            'reminders_sent' => 1,
        ]);
    }

    public function getProfileAvatarAttribute()
    {
        return $this->profile->getAvatarAttribute();
    }
}
