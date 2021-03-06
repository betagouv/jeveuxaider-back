<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Invitation extends Model
{
    use Notifiable;

    protected $guarded = ['id'];

    protected $casts = [
        'properties' => 'json',
        'last_sent_at' => 'datetime',
    ];

    // protected $with = ['invitable'];

    // protected $appends = ['is_registered'];

    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = mb_strtolower($value);
    }

    public function setPropertiesAttribute($array)
    {
        $this->attributes['properties'] = ! empty($array) ? json_encode($array) : null;
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function invitable()
    {
        return $this->morphTo();
    }

    public function scopeOfReseau($query, $reseau_id)
    {
        return $query
            ->where('invitable_type', 'App\Models\Reseau')
            ->where('invitable_id', $reseau_id)
            ->where('role', 'responsable_reseau');
    }

    public function scopeOfReseauAndRoleAntenne($query, $reseau_id)
    {
        return $query
            ->where('invitable_type', 'App\Models\Reseau')
            ->where('invitable_id', $reseau_id)
            ->where('role', 'responsable_antenne');
    }

    public function scopeOfTerritoire($query, $territoire_id)
    {
        return $query
            ->where('invitable_type', 'App\Models\Territoire')
            ->where('invitable_id', $territoire_id)
            ->where('role', 'responsable_territoire');
    }

    public function scopeOfStructure($query, $structure_id)
    {
        return $query
            ->where('invitable_type', 'App\Models\Structure')
            ->where('invitable_id', $structure_id)
            ->where('role', 'responsable_organisation');
    }

    public function getIsRegisteredAttribute()
    {
        return User::where('email', $this->email)->exists();
    }

    public function accept()
    {
        $profile = Profile::whereEmail($this->email)->first();

        // Commenter car sinon pose probl??me pour le resetContextRole + ??a va ??tre accept?? par la suite
        // if (in_array($this->role, ['responsable_organisation'])) {
        //     if ($profile->structures->count() > 0) {
        //         return;
        //     }
        // }

        if ($profile) {
            // RESPONSABLE ORGANISATION
            if ($this->role == 'responsable_organisation') {
                $this->invitable->addMember($profile, 'responsable');
            }
            // RESPONSABLE TERRITOIRE
            if ($this->role == 'responsable_territoire') {
                $this->invitable->addResponsable($profile);
            }
            // RESPONSABLE RESEAU
            if ($this->role == 'responsable_reseau') {
                $profile->update(['tete_de_reseau_id' => $this->invitable->id]);
            }
            // RESPONSABLE ANTENNE
            if ($this->role == 'responsable_antenne') {
                $this->invitable->createStructure($this->properties['antenne_name'], $profile->user);
            }
            // REFERENT DEPARTEMENTAL
            if ($this->role == 'referent_departemental') {
                $profile->update(['referent_department' => $this->properties['referent_departemental']]);
            }
            // REFERENT REGIONAL
            if ($this->role == 'referent_regional') {
                $profile->update(['referent_region' => $this->properties['referent_regional']]);
            }
            // DATAS ANALYST
            if ($this->role == 'datas_analyst') {
                $profile->update(['is_analyste' => true]);
            }
        }
    }
}
