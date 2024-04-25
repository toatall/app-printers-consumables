<?php

namespace App\Models\Auth;

use App\Models\Organization;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;


/**
 * Пользователь
 * 
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $photo_path
 * @property string $password
 * 
 * @property string $domain
 * @property string $org_code
 * @property string $company
 * @property string $fio
 * @property string $department
 * @property string $post
 * @property string $telephone
 * @property string $lotus_mail
 * @property string $members
 * 
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * 
 * @property Role[] $roles
 * @property Permission[] $permissions
 * @property Organization[] $organizations
 * 
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes, UserRolesTrait, UserOrganizationsTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'owner' => 'boolean',
        'email_verified_at' => 'datetime',
        'members' => 'array',
    ];

    /**
     * @return string
     */
    public function getOrgCode(): string
    {
        if (preg_match('/^n?\d{4}/i', $this->name, $matches) && isset($matches[0])) {
            return $matches[0];
        }
        return '0000';
    }    

    /**
     * @param Builder $query
     * @param array $filter
     */
    public function scopeFilter(Builder $query, array $filters)
    {        
        $query->whereNotNull('users.id')
            ->withTrashed()
            ->select('users.*');

        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('name', 'like', '%'.$search.'%')
                    ->orWhere('email', 'like', '%'.$search.'%')
                    ->orWhere('fio', 'like', '%'.$search.'%');
            });
        })
        ->when($filters['role'] ?? null, function(Builder $query, $role) {            
            $query->leftJoin('roles_users', 'roles_users.id_user', '=', 'users.id')
                ->leftJoin('roles', 'roles.id', '=', 'roles_users.id_role')
                ->where('roles.name', $role);
        });
    }

    // public function resolveRouteBinding($value, $field = null)
    // {
    //     return $this->where($field ?? 'id', $value)->withTrashed()->firstOrFail();
    // }

    public function availableOrganizations()
    {
        if (Auth::user()->hasRole('admin')) {
            return Organization::all();
        }
        return $this->organizations;
    }

    public function changeSelectedOrganization($code)
    {
        $this->org_code = $code;
        $this->save();
    }

    
}
