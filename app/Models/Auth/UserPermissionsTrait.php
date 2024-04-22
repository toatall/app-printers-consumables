<?php

namespace App\Models\Auth;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait UserPermissionsTrait
{

    /**
     * @return BelongsToMany
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'users_permissions', 'user_id', 'permission_id');
    }

    /**
     * @param string|array $permission
     * @return bool
     */
    public function hasPermission(string|array $permission): bool
    {     
        return $this->permissions()->whereIn('name', (array)$permission)->count() > 0; 
        
    }
    
    /**
     * @param array $permissions
     */
    public function updatePermissions($permissions)
    {        
        $this->clearPermissions();
        if ($this->hasRole('admin')) {
            return;
        }
        foreach((array)$permissions as $permission) {            
            $permissionModel = Permission::query()->where('name', $permission)->first(); 
            if ($permission && !$this->hasPermission($permission)) {
                $this->permissions()->save($permissionModel);
            }
        }
    }

    private function clearPermissions()
    {
        $this->permissions()->detach();
    }    
    
}