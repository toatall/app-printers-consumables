<?php

namespace App\Models\Auth;

use App\Models\Organization;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\DB;

/**
 * Управлениями привязками организаций к пользователю
 */
trait UserOrganizationsTrait
{

    /**
     * Организации привязанные к пользователю
     * @return BelongsToMany
     */    
    public function organizations(): BelongsToMany
    {
        return $this->belongsToMany(Organization::class, 'users_organizations', 'id_user', 'org_code');
    }

    /**
     * Проверка наличия привязки к организации с кодом $code
     * @param string|array $code
     * @return bool
     */
    public function hasOrganization(string|array $code): bool
    {     
        return $this->organizations()->whereIn('code', (array)$code)->count() > 0;         
    }
    
    /**
     * Установка организаций пользователю
     * @param array $organizations
     */
    public function updateOrganizations($organizationsCodes)
    {        
        $this->clearOrganizations();
        if ($this->hasRole('admin')) {
            return;
        }
        foreach((array)$organizationsCodes as $organizationCode) {
            $orgModel = Organization::query()->firstWhere('code', $organizationCode);
            if ($organizationCode && !$this->hasOrganization($organizationCode)) {
                $this->organizations()->save($orgModel, [
                    'created_at' => DB::raw('NOW()'),
                    'updated_at' => DB::raw('NOW()'),
                ]);
            }
        }
    }

    /**
     * Удаление привязанных организаций
     */
    private function clearOrganizations()
    {
        $this->organizations()->detach();
    }    
    
}