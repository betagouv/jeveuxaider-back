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
        $user = User::whereEmail($this->email)->first();

        // Commenter car sinon pose problème pour le resetContextRole + ça va être accepté par la suite
        // if (in_array($this->role, ['responsable_organisation'])) {
        //     if ($profile->structures->count() > 0) {
        //         return;
        //     }
        // }

        if ($user) {
            // RESPONSABLE ORGANISATION
            if ($this->role == 'responsable_organisation') {
                $this->invitable->addMember($user, 'responsable');
            }
            // RESPONSABLE TERRITOIRE
            if ($this->role == 'responsable_territoire') {
                $this->invitable->addResponsable($user->profile);
            }
            // RESPONSABLE RESEAU
            if ($this->role == 'responsable_reseau') {
                $user->profile->update(['tete_de_reseau_id' => $this->invitable->id]);
            }
            // RESPONSABLE ANTENNE
            if ($this->role == 'responsable_antenne') {
                $this->invitable->createStructure($this->properties['antenne_name'], $user);
            }
            // REFERENT DEPARTEMENTAL
            if ($this->role == 'referent_departemental') {
                $department = Department::whereNumber($this->properties['referent_departemental'])->get()->first();
                $user->assignRole('referent', $department);
            }
            // REFERENT REGIONAL
            if ($this->role == 'referent_regional') {
                $region = Region::whereNumber($this->properties['referent_regional'])->get()->first();
                $user->assignRole('referent_regional', $region);
            }
            // DATAS ANALYST
            if ($this->role == 'datas_analyst') {
                $user->profile->update(['is_analyste' => true]);
            }
        }
    }
}
