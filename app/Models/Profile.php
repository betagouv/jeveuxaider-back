<?php

namespace App\Models;

use App\Helpers\Utils;
use App\Models\Media as ModelMedia;
use App\Traits\HasMissingFields;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

// use Spatie\Tags\HasTags;

class Profile extends Model implements HasMedia
{
    use Notifiable;
    use InteractsWithMedia;
    // use HasTags;
    use LogsActivity;
    use HasMissingFields;

    protected $table = 'profiles';

    protected $guarded = [
        'id',
    ];

    protected $attributes = [
        'is_visible' => true,
    ];

    protected $casts = [
        'birthday' => 'date:Y-m-d',
        'is_visible' => 'boolean',
        'disponibilities' => 'array',
        'certifications' => 'array',
        'can_export_profiles' => 'boolean',
        'notification__responsable_bilan' => 'boolean',
        'notification__referent_bilan' => 'boolean',
        'missing_fields' => 'array',
        'latitude' => 'float',
        'longitude' => 'float',
    ];

    protected $appends = ['short_name', 'full_name', 'secret_name'];

    protected $checkFields = ['mobile', 'zip', 'type', 'disponibilities', 'commitment', 'description', 'birthday', 'skills'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = mb_strtolower($value);
    }

    public function avatar()
    {
        return $this->morphOne(ModelMedia::class, 'model')->where('collection_name', 'profile__avatar');
    }

    public function registerMediaConversions(Media $media = null): void
    {
        // 2x for high pixel density
        $this->addMediaConversion('thumbSmall')
            ->fit(Manipulations::FIT_CROP, 80, 80)
            ->nonQueued()
            ->withResponsiveImages()
            ->format(Manipulations::FORMAT_WEBP)
            ->performOnCollections('profile__avatar');

        $this->addMediaConversion('thumbMedium')
            ->fit(Manipulations::FIT_CROP, 96, 96)
            ->nonQueued()
            ->withResponsiveImages()
            ->format(Manipulations::FORMAT_WEBP)
            ->performOnCollections('profile__avatar');

        $this->addMediaConversion('thumbLarge')
            ->fit(Manipulations::FIT_CROP, 128, 128)
            ->nonQueued()
            ->withResponsiveImages()
            ->format(Manipulations::FORMAT_WEBP)
            ->performOnCollections('profile__avatar');

        $this->addMediaConversion('formPreview')
            ->fit(Manipulations::FIT_CROP, 200, 200)
            ->nonQueued()
            ->format(Manipulations::FORMAT_WEBP)
            ->performOnCollections('profile__avatar');
    }

    public function getHasUserAttribute()
    {
        return $this->user ? true : false;
    }

    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function getSecretNameAttribute()
    {
        return $this->first_name . ' ' . mb_substr($this->last_name, 0, 1) . '.';
    }

    public function getShortNameAttribute()
    {
        return mb_substr($this->first_name, 0, 2);
    }

    public function setFirstNameAttribute($value)
    {
        $this->attributes['first_name'] = Utils::ucfirst($value);
    }

    public function setLastNameAttribute($value)
    {
        $this->attributes['last_name'] = Utils::ucfirst($value);
    }

    public function scopeBenevole($query)
    {
        return $query->whereHas('user', function (Builder $query) {
            $query->where('context_role', 'volontaire')->orWhereNull('context_role');
        });
    }

    public function scopeRole($query, $contextRole)
    {
        switch ($contextRole) {
            case 'admin':
                return $query;
                break;
            case 'referent':
                $departement = Auth::guard('api')->user()->departmentsAsReferent->first()->number;

                return $query
                    ->where('zip', 'LIKE', $departement . '%')
                    ->orWhereHas(
                        'missions',
                        function (Builder $query) use ($departement) {
                            $query
                                ->whereNotNull('department')
                                ->where('department', $departement);
                        }
                    )
                    ->orWhereHas(
                        'user.structures',
                        function (Builder $query) use ($departement) {
                            $query
                                ->whereNotNull('department')
                                ->where('department', $departement);
                        }
                    );
                break;
            case 'referent_regional':
                $departements = config('taxonomies.regions.departments')[Auth::guard('api')->user()->regionsAsReferent->first()->name];

                return $query
                    ->whereHas(
                        'missions',
                        function (Builder $query) use ($departements) {
                            $query
                                ->whereNotNull('department')
                                ->whereIn('department', $departements);
                        }
                    )
                    ->orWhereHas(
                        'structures',
                        function (Builder $query) use ($departements) {
                            $query
                                ->where('role', 'responsable')
                                ->whereNotNull('department')
                                ->whereIn('department', $departements);
                        }
                    )
                    ->orWhere(
                        function (Builder $query) use ($departements) {
                            foreach ($departements as $departement) {
                                $query->orWhere('zip', 'LIKE', $departement . '%');
                            }
                        }
                    );
                break;
            case 'responsable':
                $structures_id = Auth::guard('api')->user()->structures->pluck('id')->toArray();

                return $query->whereHas(
                    'participations',
                    function (Builder $query) use ($structures_id) {
                        $query->whereHas('mission', function (Builder $query) use ($structures_id) {
                            $query->whereIn('structure_id', $structures_id);
                        });
                    }
                )
                // A VOIR SI TOUJOURS DACTUALITE
                // ->orWhereHas('user.structures', function (Builder $query) use ($structures_id) {
                //     $query->whereIn('id', $structures_id);
                // })
                ;
                break;
            default:
                abort(403, 'This action is not authorized');
                break;
        }
    }

    public function scopeDepartment($query, ...$values)
    {
        return $query->where(function ($query) use ($values) {
            if (in_array('2A', $values)) {
                $query
                    ->orWhere('zip', 'LIKE', '200%')
                    ->orWhere('zip', 'LIKE', '201%');

                $values = array_filter($values, fn ($v) => $v != '2A');
            }

            if (in_array('2B', $values)) {
                $query
                    ->orWhere('zip', 'LIKE', '202%')
                    ->orWhere('zip', 'LIKE', '206%');

                $values = array_filter($values, fn ($v) => $v != '2B');
            }

            foreach ($values as $value) {
                $query->orWhere('zip', 'LIKE', $value . '%');
            }
        });
    }

    public function scopeOfDomaine($query, $domain_id)
    {
        return $query
            ->whereHas(
                'domaines',
                function (Builder $query) use ($domain_id) {
                    $query->where('id', $domain_id);
                }
            );
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function oldReseau()
    {
        return $this->belongsTo(Reseau::class, 'old_tete_de_reseau_id');
    }

    public function missions()
    {
        return $this->belongsToMany('App\Models\Mission', 'missions_responsables', 'responsable_id', 'mission_id');
    }

    public function missionsValidatedAndOffline()
    {
        return $this->missions()
            ->where('is_online', false)
            ->where('state', 'Validée');
    }

    // Todo : supprimer cette fonction après migration des rôles
    public function oldStructures()
    {
        return $this
            ->belongsToMany('App\Models\Structure', 'old_members')
            ->withPivot('role');
    }

    public function oldTerritoires()
    {
        return $this->belongsToMany('App\Models\Territoire', 'old_profile_territoire')->orderBy('name', 'ASC');
    }

    public function participations()
    {
        return $this->hasMany('App\Models\Participation');
    }

    public function participationsValidated()
    {
        return $this->hasMany('App\Models\Participation')->where('state', 'Validée');
    }

    public function activities()
    {
        return $this->belongsToMany(Activity::class, 'activity_profile');
    }

    public function domaines()
    {
        return $this->morphToMany(Domaine::class, 'domainable')->wherePivot('field', 'profile_domaines');
    }

    public function skills()
    {
        return $this->morphToMany(Term::class, 'termable')->wherePivot('field', 'profile_skills');
    }

    public function tags()
    {
        return $this->morphToMany(Term::class, 'termable')->wherePivot('field', 'tags');
    }

    public function notificationsBenevoles()
    {
        return $this->hasMany('App\Models\NotificationBenevole');
    }

    public static function getNotificationBenevoleStats($pid)
    {
        $total = NotificationBenevole::where('profile_id', $pid)->count();
        $this_month = NotificationBenevole::where('profile_id', $pid)
            ->whereBetween('created_at', [
                Carbon::now()->startOfMonth(),
                Carbon::now()->endOfMonth(),
            ])->count();

        return [
            'total' => $total,
            'thisMonth' => $this_month,
        ];
    }

    protected function description(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => isset($value) ? strip_tags($value) : null,
        );
    }
}
