<?php
namespace App\Models;

class ConsumableTypes
{
    // $key => $label
    private static $data = [
        'cartridge' => 'Картридж',
        'drum' => 'Драм-картридж',
        'wasteContainer' => 'Контейнер для отработанного тонера',
        'other' => 'Другое',
    ];

    /**
     * @return array
     */
    public static function asArray(): array
    {
        return self::$data;
    }

    /**
     * @param string $key
     * @return string
     */
    public static function label(string $key): ?string
    {
        return self::$data[$key] ?? $key;
    }

}