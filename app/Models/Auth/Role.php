<?php

namespace App\Models\Auth;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Роль
 * 
 * @property int $id
 * @property string $name
 * @property string $description
 * 
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
        'description',
    ];
}
