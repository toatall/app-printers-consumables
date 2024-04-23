<?php
namespace App\Models;

/**
 * Трейт различных преобразований перечисления в массив
 */
trait EnumToArrayTrait
{
    /**
     * Имена перечисления
     * @return array
     */
    public static function names(): array
    {
        return array_column(self::cases(), 'name');
    }

    /**
     * Описания перечисления
     * @return array
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Имена и описания перечисления
     * [имя => описание]
     * @return array
     */
    public static function array(): array
    {
        return array_combine(self::names(), self::values());
    }
}