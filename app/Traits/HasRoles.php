<?php

namespace App\Traits;

use App\Models\Role;

trait HasRoles
{
    public function newRoles()
    {
        return $this->belongsToMany(Role::class, 'user_has_roles')->withPivot('rolable_type', 'rolable_id', 'rolable_label');
    }

    public function assignRole($roleName, $rolable = null)
    {
        if($this->hasRole($roleName, $rolable)) {
            return false;
        }
        
        $role = Role::firstWhere('name', $roleName);

        $this->newRoles()->attach($role, [
            'rolable_type' => $rolable ? $rolable::class : NULL,
            'rolable_id' => $rolable ? $rolable->id : NULL,
            'rolable_label' => $this->getRolableLabel($roleName, $rolable)
        ]);

        return $this;
    }

    public function hasRole($roles, $rolable = null)
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

    protected function getRolableLabel($roleName,  $rolable) {
        switch ($roleName) {
            case 'admin':
                return 'Modérateur';
            case 'responsable':
                return  $rolable->name;
            default:
                return NULL;
        }
    }

}
