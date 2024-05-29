<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

abstract class AbstractSeeder extends Seeder
{
    /**
     * Текущая дата для БД
     * @return \Illuminate\Database\Query\Expression
     */
    protected function getDbNowDate(): \Illuminate\Database\Query\Expression
    {
        return DB::raw('NOW()');
    }

    /**
     * Код региона
     * @return string
     */
    protected function getRegionCode(): string
    {
        $code = env('REGION_CODE');
        if (empty($code)) {
            throw new \Exception('Необходимо указать код региона "REGION_CODE" в файле .env!');
        }
        return $code;
    }
}