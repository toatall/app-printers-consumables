<?php

namespace Database\Seeders;

use App\Models\Auth\Role;

/**
 * Создание ролей
 */
class RolesSeeder extends AbstractSeeder
{

    /**
     * Run the database seeds.
     * @return void
     */
    public function run(): void
    {
        Role::query()->insert([
            // полные права (управление пользователями, ролями, справочником организаций)
            [
                'name' => 'admin', 
                'description' => 'Администратор (по всему контексту)', 
                'created_at' => $this->getDbNowDate(), 
                'updated_at' => $this->getDbNowDate(),
            ],            
            // права для работы со справочниками (управление справочниками принтеров, расходных материалов)
            [
                'name' => 'editor-dictionary', 
                'description' => 'Редактор справочников (принтеров, расходных материалов)', 
                'created_at' => $this->getDbNowDate(), 
                'updated_at' => $this->getDbNowDate(),
            ],
            // права на добавление принтеров на местах
            [
                'name' => 'editor-printer-workplace', 
                'description' => 'Редактор принтеров на местах',
                'created_at' => $this->getDbNowDate(),
                'updated_at' => $this->getDbNowDate(),
            ],
            // права на добавление расходных материалов (количества)
            [
                'name' => 'add-consumables', 
                'description' => 'Добавление расходных материалов',
                'created_at' => $this->getDbNowDate(), 
                'updated_at' => $this->getDbNowDate(),
            ],
            // права на вычитание расходного материала (количества)
            [
                'name' => 'subtract-consumable', 
                'description' => 'Вычитание расходных материалов',
                'created_at' => $this->getDbNowDate(), 
                'updated_at' => $this->getDbNowDate(),
            ],
        ]);
    }
}
