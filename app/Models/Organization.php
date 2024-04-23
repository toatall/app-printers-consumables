<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Организация
 * 
 * @property string $code
 * @property string $name
 * @property string $created_at
 * @property string $updated_at
 */
class Organization extends Model
{
    use HasFactory;

    /**
     * {@inheritDoc}
     */
    protected $table = 'organizations';

    /**
     * {@inheritDoc}
     */
    protected $primaryKey = 'code';

    /**
     * {@inheritDoc}
     */
    protected $keyType = 'string';

    /**
     * {@inheritDoc}
     */
    protected $fillable = [
        'code',
        'name',
    ];   

    /**
     * Описание аттрибутов
     * @return array
     */
    public static function labels()
    {
        return [
            'code' => 'Код',
            'name' => 'Наименование',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата изменения',
            'date' => 'Дата',
        ];
    }
}
