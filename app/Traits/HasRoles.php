<?php

namespace App\Traits;

use App\Jobs\AirtableSyncObject;
use App\Jobs\SendinblueSyncUser;
use App\Models\Role;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

trait HasRoles
{
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'rolables')->withPivot('rolable_type', 'rolable_id', 'invited_by_user_id', 'fonction');
    }

    public function assignRole($roleName, $rolable = null, $fonction = null, $invitedByUserId = null)
    {
        if ($this->hasRole($roleName)) {
            return false;
        }

        $role = Role::firstWhere('name', $roleName);

        $this->roles()->attach($role, [
            'rolable_type' => $rolable ? $rolable::class : null,
            'rolable_id' => $rolable ? $rolable->id : null,
            'fonction' => $fonction,
            'invited_by_user_id' => $invitedByUserId
        ]);

        $this->resetContextRole();

        // Sync Airtable
        if (config('services.airtable.sync')) {
            if ($roleName == 'referent' || $roleName == 'referent_regional') {
                AirtableSyncObject::dispatch($this);
            }
        }

        // Sync Sendinblue
        if (config('services.sendinblue.sync')) {
            SendinblueSyncUser::dispatch($this);
        }

        return $this;
    }

    public function removeRole($roleName)
    {
        $role = Role::firstWhere('name', $roleName);

        $this->roles()->detach($role);
        $this->resetContextRole();

        // Sync Airtable
        if (config('services.airtable.sync')) {
            if ($roleName == 'referent' || $roleName == 'referent_regional') {
                AirtableSyncObject::dispatch($this);
            }
        }

        // Sync Sendinblue
        if (config('services.sendinblue.sync')) {
            SendinblueSyncUser::dispatch($this);
        }

        return $this;
    }

    public function hasRole($roles)
    {
        if (is_string($roles)) {
            return $this->roles->contains('name', $roles);
        }

        if ($roles instanceof Role) {
            return $this->roles->contains('name', $roles->name);
        }

        if (is_array($roles)) {
            foreach ($roles as $role) {
                if ($this->hasRole($role)) {
                    return true;
                }
            }

            return false;
        }
    }

    public function scopeRole(Builder $query, $roles): Builder
    {
        $roles = array_map(function ($role) {
            if ($role instanceof Role) {
                return $role;
            }

            return Role::where('name', $role)->get()->first();
        }, Arr::wrap($roles));

        return $query->whereHas('roles', function (Builder $subQuery) use ($roles) {
            $subQuery->whereIn('name', array_column($roles, 'name'));
        });
    }
}
