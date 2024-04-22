<?php
namespace App\Models\Consumable;

use App\Models\EnumToArrayTrait;

enum ConsumableTypesEnum: string
{
    use EnumToArrayTrait;

    case cartridge = 'Картридж';
    case drum = 'Драм-картридж';
    case wasteContainer = 'Контейнер для отработанного тонера';
    case other = 'Другое';


    public static function getValueByName($name)
    {
        return self::array()[$name] ?? $name;
    }

}