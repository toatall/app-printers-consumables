<?php
namespace Database\Seeders;

use App\Models\Organization;

/**
 * Создание тестовых организаций
 */
class OrganizationSeeder extends AbstractSeeder
{    
    
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $regionCode = $this->getRegionCode();
        Organization::query()->insert([
            [
                'code' => "{$regionCode}00", 
                'name' => 'Управление', 
                'created_at' => $this->getDbNowDate(), 
                'updated_at' => $this->getDbNowDate(),
            ],
            [
                'code' => "{$regionCode}01", 
                'name' => 'Инспекция', 
                'created_at' => $this->getDbNowDate(), 
                'updated_at' => $this->getDbNowDate(),
            ],
        ]);
    }
}
