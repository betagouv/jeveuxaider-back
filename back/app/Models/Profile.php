<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use App\Helpers\Utils;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Tags\HasTags;
use Spatie\Activitylog\Traits\LogsActivity;

class Profile extends Model implements HasMedia
{
    use Notifiable, InteractsWithMedia, HasTags, LogsActivity;

    protected $table = 'profiles';

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'mobile',
        'tete_de_reseau_id',
        'referent_department',
        'referent_region',
        'birthday',
        'zip',
        'service_civique',
        'is_analyste',
        'is_visible',
        'disponibilities',
        'description',
        'commitment__duration',
        'commitment__time_period',
        'type',
        'user_id',
        'can_export_profiles',
    ];

    protected $casts = [
        'birthday' => 'date:Y-m-d',
        'is_analyste' => 'boolean',
        'is_visible' => 'boolean',
        'disponibilities' => 'array',
        'can_export_profiles' => 'boolean'
    ];

    protected $appends = ['full_name', 'short_name', 'image'];

    protected $hidden = ['media', 'user'];

    protected static $logFillable = true;

    protected static $logOnlyDirty = true;

    protected static $submitEmptyLogs = false;

    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = mb_strtolower($value);
    }

    public function getImageAttribute()
    {
        $media = $this->getFirstMedia('profiles');
        if ($media) {
            $mediaUrls = ['original' => $media->getFullUrl()];
            foreach ($media->getGeneratedConversions() as $key => $conversion) {
                $mediaUrls[$key] = $media->getUrl($key);
            }
            return $mediaUrls;
        }
        return null;
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(320)
            ->height(320)
            ->nonQueued()
            ->performOnCollections('profiles');
    }

    public function getHasUserAttribute()
    {
        return $this->user ? true : false;
    }

    public function getLastOnlineAtAttribute()
    {
        return $this->user ? $this->user->last_online_at : null;
    }

    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function getReferentWaitingActionsAttribute()
    {
        $structures = Structure::department($this->referent_department)->whereIn('state', ['En attente de validation'])->count();
        $missions = Mission::department($this->referent_department)->whereIn('state', ['En attente de validation'])->count();

        return [
            'total' => $structures + $missions,
            'structures' => $structures,
            'missions' => $missions
        ];
    }

    public function getReferentRegionWaitingActionsAttribute()
    {
        $structures = Structure::region($this->referent_region)->whereIn('state', ['En attente de validation'])->count();
        $missions = Mission::region($this->referent_region)->whereIn('state', ['En attente de validation'])->count();

        return [
            'total' => $structures + $missions,
            'structures' => $structures,
            'missions' => $missions
        ];
    }

    public function getResponsableWaitingActionsAttribute()
    {

        $structure = $this->structures->first();

        $participations = Participation::structure($structure->id)->where('state', 'En attente de validation')->count();

        return [
            'total' => $participations,
            'test' => $this->structures->first(),
        ];
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
                    ->orWhereHas(
                        'missions',
                        function (Builder $query) use ($departement) {
                            $query
                                ->whereNotNull('department')
                                ->where('department', $departement);
                        }
                    )
                    ->orWhereHas(
                        'structures',
                        function (Builder $query) use ($departement) {
                            $query
                                ->where('role', 'responsable')
                                ->whereNotNull('department')
                                ->where('department', $departement);
                        }
                    );
                break;
            case 'referent_regional':
                $departements = config('taxonomies.regions.departments')[Auth::guard('api')->user()->profile->referent_region];
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
                $structures_id =  Auth::guard('api')->user()->profile->structures->pluck('id')->toArray();
                return $query->whereHas(
                    'participations',
                    function (Builder $query) use ($structures_id) {
                        $query->whereHas('mission', function (Builder $query) use ($structures_id) {
                            $query->whereIn('structure_id', $structures_id);
                        });
                    }
                )->orWhereHas('structures', function (Builder $query) use ($structures_id) {
                    $query->whereIn('id', $structures_id);
                });
                //return $query->where('id', -1);
                break;
            default:
                abort(403, 'This action is not authorized');
                break;
        }
    }

    public function scopeDepartment($query, $value)
    {
        if ($value == '2A') {
            return $query
                ->where('zip', 'LIKE', '200%')
                ->orWhere('zip', 'LIKE', '201%');
        }

        if ($value == '2B') {
            return $query
                ->where('zip', 'LIKE', '202%')
                ->orWhere('zip', 'LIKE', '206%');
        }

        return $query->where('zip', 'LIKE', $value . '%');
    }

    public function scopeDomaine($query, $domain_id)
    {
        return $query
            ->whereHas(
                'tags',
                function (Builder $query) use ($domain_id) {
                    $query->where('id', $domain_id);
                }
            );
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function reseau()
    {
        return $this->belongsTo('App\Models\Structure');
    }

    public function teteDeReseau()
    {
        return $this->belongsTo(Reseau::class);
    }

    public function missions()
    {
        return $this->hasMany('App\Models\Mission', 'responsable_id');
    }

    public function structures()
    {
        return $this
            ->belongsToMany('App\Models\Structure', 'members')
            ->withPivot('role');
    }

    public function territoires()
    {
        return $this->belongsToMany('App\Models\Territoire')->orderBy('name', 'ASC');
    }

    public function structureAsResponsable()
    {
        return $this->structures()->wherePivot('role', 'responsable')->first();
    }

    public function participations()
    {
        return $this->hasMany('App\Models\Participation');
    }

    public function getDomainesAttribute()
    {
        return $this->tagsWithType('domaine')->values();
    }

    public function getSkillsAttribute()
    {
        return $this->tagsWithType('competence')->values();
    }

    public function isReferent()
    {
        return $this->referent_department ? true : false;
    }

    public function isReferentRegional()
    {
        return $this->referent_region ? true : false;
    }

    // public function isSuperviseur()
    // {
    //     return $this->reseau ? true : false;
    // }

    public function isTeteDeReseau()
    {
        return $this->teteDeReseau ? true : false;
    }

    public function isResponsable()
    {
        if ($this->belongsToMany('App\Models\Structure', 'members')->first() || $this->belongsToMany('App\Models\Territoire')->first()) {
            return true;
        }
        return false;
    }

    public function isAdmin()
    {
        return $this->user ? ($this->user->is_admin ? true : false) : false;
    }

    public function isVolontaire()
    {
        return $this->user ? ($this->user->context_role == 'volontaire' ? true : false) : false;
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
            // 'superviseur' => $this->isSuperviseur(),
            'responsable' => $this->isResponsable(),
            'tete_de_reseau' => $this->isTeteDeReseau(),
            'analyste' => $this->is_analyste
        ];
    }

    public function setCommitmentTotal()
    {
        $this->commitment__total = Utils::calculateCommitmentTotal(
            $this->commitment__duration,
            $this->commitment__time_period
        );
    }

    public function scopeMinimumCommitment($query, $duration, $time_period = null)
    {
        $total = Utils::calculateCommitmentTotal($duration, $time_period);
        return $query->where('commitment__total', '>=', $total);
    }


    public static function getNotificationBenevoleStats($pid)
    {
        $total = NotificationBenevole::where('profile_id', $pid)->count();
        $this_month = NotificationBenevole::where('profile_id', $pid)
            ->whereBetween('created_at', [
                Carbon::now()->startOfMonth(),
                Carbon::now()->endOfMonth()
            ])->count();

        return [
            'total' => $total,
            'thisMonth' => $this_month,
        ];
    }

    public function getNotificationBenevoleStatsAttribute()
    {
        return self::getNotificationBenevoleStats($this->id);
    }
}
