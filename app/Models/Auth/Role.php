<?php

namespace App\Models\Auth;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Роль
 * 
 * @property int $id
 * @property string $name
 */
class Role extends Model
{
    use HasFactory;

    /**
     * {@inheritDoc}
     */
    protected $table = 'roles';
    
    /**
     * {@inheritDoc}
     */
    protected $fillable = [
        'name',
    ];
}
