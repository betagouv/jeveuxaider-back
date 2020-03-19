<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\Models\Media;
use Illuminate\Http\UploadedFile;
use App\Helpers\Utils;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class Structure extends Model implements HasMedia
{
    use CrudTrait, HasMediaTrait, SoftDeletes;

    const CEU_TYPES = [
        "SDIS (Service départemental d'Incendie et de Secours)",
        'Gendarmerie',
        'Police',
        'Armées'
    ];

    protected $table = 'structures';

    protected $fillable = [
        'name',
        'logo',
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
        'state' => 'Validée',
        'country' => 'France'
    ];

    protected $casts = [
        'is_reseau' => 'boolean',
        'association_types' => 'array',
        'latitude' => 'float',
        'longitude' => 'float'
    ];

    protected $hidden = ['media'];

    protected $appends = ['logo', 'full_address', 'ceu'];

    protected $withCount = ['missions'];

    protected $with = ['members:id,first_name,last_name'];

    public function scopeRole($query, $contextRole)
    {
        switch ($contextRole) {
            case 'admin':
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
            case 'superviseur':
                return $query
                    ->whereNotNull('reseau_id')
                    ->where('reseau_id', Auth::guard('api')->user()->profile->reseau->id);
            break;
        }
    }

    public function registerMediaCollections()
    {
        $this->addMediaCollection('logos')
            ->singleFile();
    }

    public function registerMediaConversions(Media $media = null)
    {
        $this->addMediaConversion('medium')
            ->width(400)
            ->height(300);
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

    public function setLogoAttribute($logo)
    {
        if ($logo == null) {
            $logo = $this->getMedia('logos')->first();
            if ($logo) {
                $logo->delete();
            }
        } elseif (Str::startsWith($logo, 'data:image')) {
            $this->addMediaFromBase64($logo)->toMediaCollection('logos');
        } elseif ($logo instanceof UploadedFile) {
            $this->addMedia($logo)->toMediaCollection('logos');
        }
    }

    public function getLogoAttribute()
    {
        $logo = $this->getMedia('logos')->first();

        return isset($logo) ? $logo->getFullUrl('medium') : null;
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
        return $this->hasMany('App\Models\Mission')->without(['structure']);
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

    public function addMission($mission)
    {
        return $this->missions()->create($mission);
    }
}
