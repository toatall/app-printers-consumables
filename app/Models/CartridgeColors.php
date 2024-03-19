<?php
namespace App\Models;

class CartridgeColors
{

    private static $_colors = [
        'black' => [
            'name' => 'Черный (black)',
            'color' => 'bg-black',
        ],
        'blue' => [
            'name' => 'Голубой (blue)',
            'color' => 'bg-blue-500',
        ],
        'yellow' => [
            'name' => 'Желтый (yellow)',
            'color' => 'bg-yellow-500',
        ],
        'magenta' => [
            'name' => 'Пурпурный (magenta)',
            'color' => 'bg-purple-500',
        ],        
    ];

    public static function asArray()
    {
        return self::$_colors;
    }

}