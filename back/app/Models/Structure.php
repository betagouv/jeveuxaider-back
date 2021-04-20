<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\Utils;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Tags\HasTags;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;

class Structure extends Model
{
    use SoftDeletes, LogsActivity, HasRelationships, HasTags;

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
        'donation',
        'reseau_id',
        'is_reseau',
        'state',
        'publics_beneficiaires',
        'image_1',
        'image_2',
    ];

    protected $attributes = [
        'state' => 'En attente de validation',
        'country' => 'France',
    ];

    protected $casts = [
        'is_reseau' => 'boolean',
        'association_types' => 'array',
        'latitude' => 'float',
        'longitude' => 'float',
        'publics_beneficiaires' => 'array',
    ];

    protected $hidden = ['media'];

    protected $appends = ['full_address', 'domaines'];
    // protected $with = ['collectivity'];

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
            case 'responsable_collectivity':
                return $query->collectivity(Auth::guard('api')->user()->profile->collectivity->id);
            break;
        }
    }

    public function getDomainesAttribute()
    {
        return $this->tagsWithType('domaine')->map(function ($item) {
            return $item->id;
        });
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

    public function scopeRegion($query, $value)
    {
        return $query
            ->whereIn('department', config('taxonomies.regions.departments')[$value]);
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

    public function collectivity()
    {
        return $this->hasOne('App\Models\Collectivity');
    }

    public function scopeCollectivity($query, $collectivity_id)
    {
        $collectivity = Collectivity::find($collectivity_id);

        if ($collectivity->type == 'commune') {
            return $query
                ->whereIn('zip', $collectivity->zips);
        }
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

    public function invitations()
    {
        return $this->morphMany('App\Models\Invitation', 'invitable');
    }

    public function responsables()
    {
        return $this->belongsToMany('App\Models\Profile', 'members')->wherePivot('role', 'responsable');
    }

    public function missions()
    {
        return $this->hasMany('App\Models\Mission');
    }

    public function participations()
    {
        return $this->hasManyThrough('App\Models\Participation', 'App\Models\Mission');
    }

    public function waitingParticipations()
    {
        return $this->hasManyThrough('App\Models\Participation', 'App\Models\Mission')
            ->where('participations.state', 'En attente de validation');
    }

    public function conversations()
    {
        return $this->hasManyDeep(
            'App\Models\Conversation',
            ['App\Models\Mission', 'App\Models\Participation'],
            [null, null, ['conversable_type', 'conversable_id']]
        );
    }

    public function addMember(Profile $profile, $role)
    {
        return $this->members()->attach($profile, ['role' => $role]);
    }

    public function deleteMember(Profile $profile)
    {
        $this->members()->detach($profile);
        $this->resetResponsable($profile);

        return $this->load('members');
    }

    public function resetResponsable(Profile $profile)
    {
        $newResponsableProfileId = $this->members->where('id', '!=', $profile->id)->pluck('id')->first();
        if ($newResponsableProfileId) {
            Mission::where('responsable_id', $profile->id)->update(['responsable_id' => $newResponsableProfileId]);
        }
    }

    public function addMission($values)
    {
        $mission = $this->missions()->create($values);

        if ($values['tags']) {
            $mission->syncTagsWithType($values['tags'], 'domaine');
        }

        return $mission;
    }

    public function setResponseRatio()
    {
        $participationsCount = $this->participations->count();
        $waitingParticipationsCount = $this->participations->where('state', 'En attente de validation')->count();
        $this->response_ratio = round(($participationsCount - $waitingParticipationsCount) / $participationsCount * 100);

        return $this;
    }

    public function setResponseTime()
    {
        $avgResponseTime = $this->conversations->avg('response_time');
        if ($avgResponseTime) {
            $this->response_time = intval($avgResponseTime);
        }
        return $this;
    }

    // TEMP LARAVEL 7. DISPO DANS LARAVEL 8
    public function saveQuietly(array $options = [])
    {
        return static::withoutEvents(function () use ($options) {
            return $this->save($options);
        });
    }
}
