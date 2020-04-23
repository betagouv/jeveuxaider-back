<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use App\Notifications\ResetPassword;
use Carbon\Carbon;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'context_role',
    ];

    protected $hidden = [
        'password', 'remember_token', 'is_admin', 'email_verified_at'
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $with = ['profile'];

    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = strtolower($value);
    }

    public function profile()
    {
        return $this->hasOne('App\Models\Profile');
    }

    public function isAdmin()
    {
        return $this->is_admin;
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }

    public function missions()
    {
        return $this->hasMany('App\Models\Mission');
    }

    public function structures()
    {
        return $this->hasMany('App\Models\Structure');
    }

    public function getContextRoleAttribute()
    {
        if ($this->attributes['context_role'] == null || $this->attributes['context_role'] == 'volontaire') {
            $userRoles = array_filter($this->profile->roles, function ($role) {
                return $role === true;
            });
            if (count($userRoles) > 0) {
                $this->attributes['context_role'] = array_key_first($userRoles);
            }
        }

        return $this->attributes['context_role'];
    }

    public function anonymize()
    {
        $email = $this->id . '@anonymized.fr';
        $this->anonymous_at = Carbon::now();
        $this->name = $email;
        $this->email = $email;
        $this->profile->email = $email;
        $this->profile->first_name = 'Anonyme';
        $this->profile->last_name = 'Anonyme';
        $this->profile->phone = null;
        $this->profile->mobile = null;
        $this->profile->birthday = null;
        $this->save();
        $this->profile->save();

        return $this;
    }
}
