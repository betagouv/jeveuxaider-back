<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\Utils;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Traits\LogsActivity;

class Structure extends Model
{
    use SoftDeletes, LogsActivity;

    const CEU_TYPES = [
        "SDIS (Service départemental d'Incendie et de Secours)",
        'Gendarmerie',
        'Police',
        'Armées'
    ];

    protected $table = 'structures';

    protected $fillable = [
        'name',
        'user_id',
        'siret',
        'statut_juridique',
        'association_types',
        'structure_publique_type',
        'structure_publique_etat_type',
        'structure_privee_type',
        'description',
        'address',
        'latitude',
        'longitude',
        'zip',
        'city',
        'department',
        'country',
        'website',
        'facebook',
        'twitter',
        'instagram',
        'reseau_id',
        'is_reseau',
        'state',
    ];

    protected $attributes = [
        'state' => 'En attente de validation',
        'country' => 'France'
    ];

    protected $casts = [
        'is_reseau' => 'boolean',
        'association_types' => 'array',
        'latitude' => 'float',
        'longitude' => 'float'
    ];

    protected $hidden = ['media'];

    protected $appends = ['full_address', 'ceu'];

    protected static $logFillable = true;

    protected static $logOnlyDirty = true;

    protected static $submitEmptyLogs = false;

    public function scopeRole($query, $contextRole)
    {
        switch ($contextRole) {
            case 'admin':
            case 'analyste':
                return $query;
            break;
            case 'responsable':
                return $query->whereHas('responsables', function (Builder $query) {
                    $query->where('profile_id', Auth::guard('api')->user()->profile->id);
                });
            break;
            case 'tuteur':
                return $query->whereHas('tuteurs', function (Builder $query) {
                    $query->where('profile_id', Auth::guard('api')->user()->profile->id);
                });
            break;
            case 'referent':
                return $query
                    ->whereNotNull('department')
                    ->where('department', Auth::guard('api')->user()->profile->referent_department);
            break;
            case 'referent_regional':
                return $query
                    ->whereNotNull('department')
                    ->whereIn('department', config('taxonomies.regions.departments')[Auth::guard('api')->user()->profile->referent_region]);
            break;
            case 'superviseur':
                return $query
                    ->whereNotNull('reseau_id')
                    ->where('reseau_id', Auth::guard('api')->user()->profile->reseau->id);
            break;
        }
    }

    public function getResponseRatioAttribute()
    {
        $participationsCount = Participation::whereIn('mission_id', $this->missions->pluck('id'))->count();

        if ($this->missions->count() == 0 || !$participationsCount) {
            return null;
        }

        $participationsWaitingCount = Participation::where('state', 'En attente de validation')->whereIn('mission_id', $this->missions->pluck('id'))->count();

        return round(($participationsCount - $participationsWaitingCount) / $participationsCount * 100);
    }

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = Utils::ucfirst($value);
    }

    public function setAssociationTypesAttribute($value)
    {
        $this->attributes['association_types'] = ($this->statut_juridique == 'Association') ? json_encode($value) : null;
    }

    public function setStructurePubliqueTypeAttribute($value)
    {
        $this->attributes['structure_publique_type'] = ($this->statut_juridique == 'Structure publique') ? $value : null;
    }

    public function setStructurePubliqueEtatTypeAttribute($value)
    {
        $this->attributes['structure_publique_etat_type'] = ($this->statut_juridique == 'Structure publique') ? $value : null;
    }

    public function setStructurePriveeTypeAttribute($value)
    {
        $this->attributes['structure_privee_type'] = ($this->statut_juridique == 'Structure privée') ? $value : null;
    }

    public function scopeCeu($query, $value)
    {
        if ($value) {
            return $query
                ->whereIn('structure_publique_etat_type', self::CEU_TYPES);
        }
        return $query
                ->whereNull('structure_publique_etat_type')
                ->orWhereNotIn('structure_publique_etat_type', self::CEU_TYPES);
    }

    public function scopeDepartment($query, $value)
    {
        return $query
            ->where('department', $value);
    }

    public function scopeDomaine($query, $domain_id)
    {
        return $query
            ->whereHas('missions', function (Builder $query) use ($domain_id) {
                $query->where('domaine_id', $domain_id)
                ->orWhereHas('tags', function (Builder $query) use ($domain_id) {
                    $query->where('id', $domain_id);
                });
            });
    }

    public function scopeValidated($query)
    {
        return $query->where('state', 'Validée');
    }

    public function getCeuAttribute()
    {
        if (!isset($this->attributes['structure_publique_etat_type'])) {
            return false;
        }

        return in_array($this->attributes['structure_publique_etat_type'], self::CEU_TYPES) ? true : false;
    }

    public function getFullAddressAttribute()
    {
        return "{$this->address}, {$this->zip} {$this->city}";
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function reseau()
    {
        return $this->belongsTo('App\Models\Structure');
    }

    public function members()
    {
        return $this->belongsToMany('App\Models\Profile', 'members')->withPivot('role');
    }

    public function responsables()
    {
        return $this->belongsToMany('App\Models\Profile', 'members')->wherePivot('role', 'responsable');
    }

    public function tuteurs()
    {
        return $this->belongsToMany('App\Models\Profile', 'members')->wherePivot('role', 'tuteur');
    }

    public function missions()
    {
        return $this->hasMany('App\Models\Mission');
    }

    public function addMember(Profile $profile, $role)
    {
        return $this->members()->attach($profile, ['role' => $role]);
    }

    public function deleteMember(Profile $profile)
    {
        $this->members()->detach($profile);
        return $this->load('members');
    }

    public function addMission($values)
    {
        $mission = $this->missions()->create($values);

        if ($values['tags']) {
            $mission->syncTagsWithType($values['tags'], 'domaine');
        }

        return $mission;
    }
}
