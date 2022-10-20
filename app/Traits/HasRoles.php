<?php

namespace App\Traits;

use App\Models\Role;

trait HasRoles
{
    public function newRoles()
    {
        return $this->belongsToMany(Role::class, 'user_has_roles');
    }

    public function assignRole($roleName)
    {
        $role = Role::firstWhere('name', $roleName);
        $this->newRoles()->attach($role);

        return $this;
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

}
