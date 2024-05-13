<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Participation extends Model
{
    use LogsActivity;
    use HasFactory;

    protected $table = 'participations';

    protected $attributes = [
        'state' => 'En attente de validation',
    ];

    protected $guarded = [
        'id',
    ];

    protected $casts = [
        'old_slots' => 'array',
        'slots' => 'json',
    ];

    public const ACTIVE_STATUS = [
        'En attente de validation',
        'En cours de traitement',
        'Validée',
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

    public function profile()
    {
        return $this->belongsTo('App\Models\Profile');
    }

    public function conversation()
    {
        return $this->morphOne('App\Models\Conversation', 'conversable');
    }

    public function tags()
    {
        return $this->morphToMany(StructureTag::class, 'taggable', 'structures_taggables');
    }

    public function scopeRole($query, $contextRole)
    {
        switch ($contextRole) {
            case 'admin':
                return $query;
                break;
            case 'referent':
                return $query
                    ->whereHas('mission', function (Builder $query) {
                        $query->where('department', Auth::guard('api')->user()->departmentsAsReferent->first()->number);
                    });
                break;
            case 'referent_regional':
                return $query
                    ->whereHas('mission', function (Builder $query) {
                        $query->whereIn('department', config('taxonomies.regions.departments')[Auth::guard('api')->user()->regionsAsReferent->first()->name]);
                    });
                break;
            case 'tete_de_reseau':
                return $query->ofReseau(Auth::guard('api')->user()->contextable_id);
                break;
            case 'responsable':
                return $query->ofStructure(Auth::guard('api')->user()->contextable_id);
                break;
            case 'responsable_territoire':
                return $query->ofTerritoire(Auth::guard('api')->user()->contextable_id);
                break;
            default:
                abort(403, 'This action is not authorized');
                break;
        }
    }

    public function scopeOfStructure($query, $structure_id)
    {
        return $query->whereHas('mission', function (Builder $query) use ($structure_id) {
            $query->where('structure_id', $structure_id);
        });
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
                $query->where('structure_id', $value);
            });
    }

    public function scopeOfResponsable($query, $value)
    {
        return $query
            ->whereHas('mission', function (Builder $query) use ($value) {
                $query->where('responsable_id', $value);
            });
    }

    public function scopeOfDomaine($query, ...$values)
    {
        return $query
            ->whereHas('mission', function (Builder $query) use ($values) {
                $query->ofDomaine(...$values);
            });
    }

    public function scopeOfActivity($query, ...$values)
    {
        return $query
            ->whereHas('mission', function (Builder $query) use ($values) {
                $query->ofActivity(...$values);
            });
    }

    public function scopeOfTerritoire($query, $territoire_id)
    {
        return $query->whereHas('mission', function (Builder $query) use ($territoire_id) {
            $query->ofTerritoire($territoire_id);
        });
    }

    public function scopeOfTemplate($query, $template_id)
    {
        return $query->whereHas('mission', function (Builder $query) use ($template_id) {
            $query->ofTemplate($template_id);
        });
    }

    public function scopeNeedToBeTreated($query)
    {
        return $query->where(function ($query) {
            $query->where(function ($query) {
                $query->where('participations.state', 'En attente de validation')
                    ->where('participations.created_at', '<', Carbon::now()->subDays(7)->startOfDay());
            })
            ->orWhere(function ($query) {
                $query
                    ->where('participations.state', 'En cours de traitement')
                    ->where('participations.created_at', '<', Carbon::now()->subMonths(2)->startOfDay());
            });
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
}
