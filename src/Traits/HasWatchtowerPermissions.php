<?php

namespace Aguaralabs\Watchtower\Traits;

use Aguaralabs\Watchtower\Models\Role;
use Aguaralabs\Watchtower\Models\Permission;

trait HasWatchtowerPermissions
{
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_role');
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'permission_user');
    }

    /**
     * Verifica si el usuario tiene un permiso específico.
     */
    public function hasPermission($slug)
    {
        if ($this->permissions->contains('slug', $slug)) {
            return true;
        }

        return $this->roles->filter(function ($role) use ($slug) {
                return $role->permissions->contains('slug', $slug);
            })->count() > 0;
    }

    public function hasRole($roleSlug)
    {
        return $this->roles->contains('slug', $roleSlug);
    }
}