<?php

namespace App\Traits;

use App\Models\Role;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

trait HasRoles
{
    public function newRoles()
    {
        return $this->belongsToMany(Role::class, 'rolables')->withPivot('rolable_type', 'rolable_id');
    }

    public function assignRole($roleName, $rolable = null, $fonction = null)
    {
        if($this->hasRole($roleName)) {
            return false;
        }
        
        $role = Role::firstWhere('name', $roleName);

        $this->newRoles()->attach($role, [
            'rolable_type' => $rolable ? $rolable::class : NULL,
            'rolable_id' => $rolable ? $rolable->id : NULL,
            'fonction' => $fonction
        ]);

        return $this;
    }

    public function removeRole($roleName)
    {
        $role = Role::firstWhere('name', $roleName);

        $this->newRoles()->detach($role);
    }

    public function hasRole($roles)
    {
        if (is_string($roles)) {
            return $this->newRoles->contains('name', $roles);
        }

        if ($roles instanceof Role) {
            return $this->newRoles->contains('name', $roles->name);
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

        return $query->whereHas('newRoles', function (Builder $subQuery) use ($roles) {
            $subQuery->whereIn('name', array_column($roles, 'name'));
        });
    }
}
