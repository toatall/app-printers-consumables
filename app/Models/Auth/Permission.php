<?php

namespace App\Models\Auth;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    /**
     * {@inheritDoc}
     */
    protected $table = 'permissions';

    /**
     * {@inheritDoc}
     */
    protected $fillable = [
        'name',
    ];
}
