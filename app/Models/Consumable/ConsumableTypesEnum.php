<?php
namespace App\Models\Consumable;

use App\Models\EnumToArrayTrait;

/**
 * Типы расходных материалов
 */
enum ConsumableTypesEnum: string
{
    use EnumToArrayTrait;

    case cartridge = 'Картридж';
    case drum = 'Драм-картридж';
    case wasteContainer = 'Контейнер для отработанного тонера';
    case other = 'Другое';

    /**
     * Описание расходного материала по его имени
     * @param string $name
     * @return string
     */
    public static function getValueByName($name)
    {
        return self::array()[$name] ?? $name;
    }

}