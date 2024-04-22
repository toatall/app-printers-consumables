<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\Auth\LdapFinder;
use App\Models\Auth\Permission;
use App\Models\Auth\Role;
use App\Models\Auth\User;
use App\Models\Auth\UserChecker;
use App\Models\Organization;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Inertia\Inertia;

class UsersController extends Controller
{

    /**
     * Доступные роли
     * @return mixed
     */
    private function roles(): mixed
    {
        return Role::all()->transform(fn(Role $role) => [
            'name' => $role->name,
        ]);
    }

    // private function permissions()
    // {
    //     return Permission::all()->transform(fn(Permission $permission) => [
    //         'name' => $permission->name,
    //     ]);
    // }

    private function organizations()
    {
        return Organization::all()->transform(fn(Organization $organization) => [
            'code' => $organization->code,
            'name' => $organization->name,
        ]);
    }

    /**
     * Данные о пользователе
     * @return callable
     */
    private function transformUser()
    {
        return fn(User $user) => [
            'id'=> $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'org_code' => $user->org_code,
            'fio' => $user->fio,
            'department' => $user->department,
            'lotus_mail' => $user->lotus_mail,
            'photo' => $user->photo_path ? URL::route('image', ['path' => $user->photo_path, 'w' => 40, 'h' => 40, 'fit' => 'crop']) : null,
            'roles' => $user->roles,
            'permissions' => $user->permissions,
            'deleted_at' => $user->deleted_at,
        ];
    }   

    
    public function index()
    {        
        return Inertia::render('Users/Index', [
            'filters' => Request::all(['search', 'role']),
            'users' => User::filter(Request::only(['search', 'role']))                
                ->get()
                ->transform($this->transformUser()),
            'roles' => $this->roles(),
            'rolesLabels' => User::rolesLabels(),           
        ]);
    }


    public function create()
    {
        return Inertia::render('Users/Create');
    }

    public function store(UserRequest $request)
    {
        $userFactory = new UserChecker(new LdapFinder(
            host: env('LDAP_SERVER_NAME'),
            port: env('LDAP_SERVER_PORT'),
            dn: env('LDAP_BASE_DN'),
            username: env('LDAP_BIND_USERNAME'),
            password: env('LDAP_BIND_PASSWORD')
        ));
        if ($userFactory->checkUser($request->name) == null) {
            return Session::flash('error', "Пользователь {$request->name} не найден в ЕСК!");
        }
        $user = $userFactory->findOrCreate("$request->name");

        return Redirect::route('users.edit', [$user])->with('success', 'Пользователь успешно создан!');
    }

    public function edit(User $user)
    {
        if (Auth::check() && !Auth::user()->hasRole('admin')) {
            if (Auth::user()->id !== $user->id) {
                abort(403);
            }
        }

        return Inertia::render('Users/Edit', [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'photo' => $user->photo_path ? URL::route('image', ['path' => $user->photo_path, 'w' => 60, 'h' => 60, 'fit' => 'crop']) : null,
                'deleted_at' => $user->deleted_at,
                'roles' => $user->roles()->get()->transform(fn(Role $role) => [
                    'name' => $role->name,
                ]),                
                'organizations' => $user->organizations,
                'fio' => $user->fio,
                'domain' => $user->domain,
                'org_code' => $user->org_code,
                'company' => $user->company,
                'department' => $user->department,
                'post' => $user->post,
                'telephone' => $user->telephone,
                'lotus_mail' => $user->lotus_mail,
            ],
            'roles' => $this->roles(),             
            'organizations' => $this->organizations(),
            'rolesLabels' => User::rolesLabels(),           
        ]);
    }

    public function update(User $user)
    {
        Request::validate([         
            'photo' => ['nullable', 'image'],
        ]);

        if (Request::file('photo')) {
            $user->update(['photo_path' => Request::file('photo')->store('users')]);
        }

        if (Auth::user()->hasRole('admin')) {
            $user->updateRoles(Request::get('selectedRoles'));
            //$user->updatePermissions(Request::get('selectedPermissions'));
            $user->updateOrganizations(Request::get('selectedOrganizations'));
        }

        return Redirect::back()->with('success', 'Данные пользователя обновлены.');
    }

    public function destroy(User $user)
    {        
        $user->delete();

        return Redirect::back()->with('success', 'Пользователь удален.');
    }

    public function restore(User $user)
    {
        $user->restore();

        return Redirect::back()->with('success', 'Пользователь восстановлен.');
    }

    
    
}
