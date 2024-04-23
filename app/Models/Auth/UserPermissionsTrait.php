<?php

namespace App\Models\Auth;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Управление разрешениями пользователя
 */
trait UserPermissionsTrait
{

    /**
     * Привязанные разрешения к пользователю
     * @return BelongsToMany
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'users_permissions', 'user_id', 'permission_id');
    }

    /**
     * Проверка разрешения(ий) $permission у пользователя 
     * @param string|array $permission
     * @return bool
     */
    public function hasPermission(string|array $permission): bool
    {     
        return $this->permissions()->whereIn('name', (array)$permission)->count() > 0; 
        
    }
    
    /**
     * Установка разрешений
     * @param array $permissions пользователю
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

    /**
     * Удаление всех разрешений у пользователя
     */
    private function clearPermissions()
    {
        $this->permissions()->detach();
    }    
    
}