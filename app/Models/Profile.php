<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\Models\Media;
use Illuminate\Http\UploadedFile;
use Illuminate\Notifications\Notifiable;
use App\Helpers\Utils;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class Profile extends Model implements HasMedia
{
    use CrudTrait;
    use HasMediaTrait;
    use Notifiable;

    protected $table = 'profiles';

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        // 'avatar',
        'phone',
        'mobile',
        'reseau_id',
        'referent_department',
        'referent_region',
        'birthday',
        'zip',
        'service_civique',
        'is_analyste'
    ];

    protected $casts = [
        'is_analyste' => 'boolean',
    ];

    protected $appends = ['full_name', 'short_name', 'avatar', 'roles', 'has_user', 'volontaire'];

    protected $hidden = ['media', 'user'];

    protected $with = ['structures:id,name', 'reseau:id,name', 'participations'];

    public function registerMediaCollections()
    {
        $this->addMediaCollection('avatars')->singleFile();
    }

    public function registerMediaConversions(Media $media = null)
    {
        $this->addMediaConversion('thumb')->width(120)->height(120);
    }

    public function getHasUserAttribute()
    {
        return $this->user ? true : false;
    }

    public function setAvatarAttribute($avatar)
    {
        if ($avatar == null) {
            $avatar = $this->getMedia('avatars')->first();
            if ($avatar) {
                $avatar->delete();
            }
            return;
        }

        if (Str::startsWith($avatar, 'data:image')) {
            $this->addMediaFromBase64($avatar)->toMediaCollection('avatars');
        } elseif ($avatar instanceof UploadedFile) {
            $this->addMedia($avatar)->toMediaCollection('avatars');
        }
    }

    public function getAvatarAttribute()
    {
        $avatar = $this->getMedia('avatars')->first();

        return isset($avatar) ? $avatar->getFullUrl('thumb') : null;
    }

    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function getShortNameAttribute()
    {
        return mb_substr($this->first_name, 0, 1) . mb_substr($this->last_name, 0, 1);
    }

    public function setFirstNameAttribute($value)
    {
        $this->attributes['first_name'] = Utils::ucfirst($value);
    }

    public function setLastNameAttribute($value)
    {
        $this->attributes['last_name'] = Utils::ucfirst($value);
    }

    public function scopeRole($query, $contextRole)
    {
        switch ($contextRole) {
            case 'admin':
            case 'analyste':
                return $query;
            break;
            case 'referent':
                $departement = Auth::guard('api')->user()->profile->referent_department;
                return $query
                    ->where('zip', 'LIKE', $departement . '%')
                    ->orWhereHas('missions', function (Builder $query) use ($departement) {
                        $query
                            ->whereNotNull('department')
                            ->where('department', $departement);
                    })
                    ->orWhereHas('structures', function (Builder $query) use ($departement) {
                        $query
                            ->where('role', 'responsable')
                            ->whereNotNull('department')
                            ->where('department', $departement);
                    })
                ;
            break;
            case 'referent_regional':
                $departements = config('taxonomies.regions.departments')[Auth::guard('api')->user()->profile->referent_region];
                return $query
                    ->whereHas('missions', function (Builder $query) use ($departements) {
                        $query
                            ->whereNotNull('department')
                            ->whereIn('department', $departements);
                    })
                    ->orWhereHas('structures', function (Builder $query) use ($departements) {
                        $query
                            ->where('role', 'responsable')
                            ->whereNotNull('department')
                            ->whereIn('department', $departements);
                    })
                    ->orWhere(function (Builder $query) use ($departements) {
                        foreach ($departements as $departement) {
                            $query->orWhere('zip', 'LIKE', $departement . '%');
                        }
                    })
                ;
            break;
            case 'superviseur':
                return $query
                    ->whereHas('structures', function (Builder $query) {
                        $query
                            ->whereNotNull('reseau_id')
                            ->where('reseau_id', Auth::guard('api')->user()->profile->reseau_id);
                    })
                ;
            break;
        }
    }

    public function scopeDepartment($query, $value)
    {
        return $query->where('zip', 'LIKE', $value . '%');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function reseau()
    {
        return $this->belongsTo('App\Models\Structure')->without('members');
    }

    public function missions()
    {
        return $this->hasMany('App\Models\Mission', 'tuteur_id');
    }

    public function structures()
    {
        return $this
            ->belongsToMany('App\Models\Structure', 'members')
            ->without('members')
            ->withPivot('role');
    }

    public function participations()
    {
        return $this
            ->hasMany('App\Models\Participation')
            ->without('profile')
            ->without('mission');
    }

    public function isReferent()
    {
        return $this->referent_department ? true : false;
    }

    public function isReferentRegional()
    {
        return $this->referent_region ? true : false;
    }

    public function isSuperviseur()
    {
        return $this->reseau ? true : false;
    }

    public function isResponsable()
    {
        return (boolean) $this->belongsToMany('App\Models\Structure', 'members')->wherePivot('role', 'responsable')->first();
    }

    public function isTuteur()
    {
        return (boolean) $this->belongsToMany('App\Models\Structure', 'members')->wherePivot('role', 'tuteur')->first();
    }

    public function isAdmin()
    {
        return $this->user ? $this->user->is_admin ? true : false : false;
    }

    public function isVolontaire()
    {
        return $this->user ? $this->user->context_role == 'volontaire' ? true : false : false;
    }

    public function getVolontaireAttribute()
    {
        return $this->isVolontaire();
    }

    public function getRolesAttribute()
    {
        return [
            'admin' => $this->isAdmin(),
            'referent' => $this->isReferent(),
            'referent_regional' => $this->isReferentRegional(),
            'superviseur' => $this->isSuperviseur(),
            'responsable' => $this->isResponsable(),
            'tuteur' => $this->isTuteur(),
            'analyste' => $this->is_analyste
        ];
    }
}
