<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class NotificationTemoignage extends Model
{
    protected $table = 'notifications_temoignages';

    protected $casts = [
        'last_sent_at' => 'datetime',
    ];

    protected $fillable = [
        'participation_id',
        'token',
        'reminders_sent',
        'last_sent_at',
    ];

    public function participation()
    {
        return $this->belongsTo('App\Models\Participation');
    }

    public function scopeRole($query, $contextRole)
    {
        switch ($contextRole) {
            case 'admin':
                return $query;
                break;
            case 'referent':
                return $query
                    ->whereHas('participation', function (Builder $query) {
                        $query->whereHas('mission', function (Builder $query) {
                            $query->where('department', Auth::guard('api')->user()->departmentsAsReferent->first()->number);
                        });
                    });
                break;
            case 'referent_regional':
                return $query
                    ->whereHas('participation', function (Builder $query) {
                        $query->whereHas('mission', function (Builder $query) {
                            $query->whereIn('department', config('taxonomies.regions.departments')[Auth::guard('api')->user()->regionsAsReferent->first()->name]);
                        });
                    });
                break;
            case 'tete_de_reseau':
                return $query
                    ->whereHas('participation.mission.structure.reseaux', function (Builder $query) {
                        $query->where('reseaux.id', Auth::guard('api')->user()->contextable_id);
                    });
                break;
            case 'responsable':
                $user = Auth::guard('api')->user();

                return $query
                    ->whereHas('participation', function (Builder $query) use ($user) {
                        $query->whereHas('mission', function (Builder $query) use ($user) {
                            if ($user->context_role == 'responsable' && $user->contextable_type == 'structure' && ! empty($user->contextable_id)) {
                                $query->where('structure_id', $user->contextable_id);
                            } else {
                                $query->where('structure_id', $user->structures->pluck('id')->first());
                            }
                        });
                    });
                break;
            default:
                abort(403, 'This action is not authorized');
                break;
        }
    }
}
