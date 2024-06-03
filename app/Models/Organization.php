<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Организация
 * 
 * @property string $code
 * @property string $parent
 * @property string $name
 * @property string $created_at
 * @property string $updated_at
 * 
 * @property Organization $parentOrganization
 * @property Organization[] $childOrganizations
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
        'parent',
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
            'parent' => 'Код вышестоящей организации',
            'name' => 'Наименование',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата изменения',
            'date' => 'Дата',
        ];
    }

    /**
     * Родительская организация
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parentOrganization()
    {
        return $this->belongsTo(Organization::class,  'parent');
    }

    /**
     * Подведомственные организации
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function childOrganizations()
    {
        return $this->hasMany(Organization::class, 'parent', 'code');
    }

}
