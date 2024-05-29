<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\Auth\LdapFinder;
use App\Models\Auth\Role;
use App\Models\Auth\User;
use App\Models\Auth\UserFactory;
use App\Models\Organization;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Inertia\Inertia;

/**
 * Управление пользователями
 */
class UsersController extends Controller
{

    /**
     * {@inheritDoc}
     */
    public function __construct()
    {
        // настройка прав доступа
        $this->middleware('role:admin')
            ->only(['create', 'store', 'destroy']);
    }


    /**
     * Все доступные роли
     * @return \Illuminate\Support\Collection
     */
    private function roles(): mixed
    {
        return Role::all()->transform(fn(Role $role) => [
            'name' => $role->name,
            'description' => $role->description,
        ]);
    }

    /**
     * Все организации
     * @return \Illuminate\Support\Collection
     */
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

    /**
     * Список пользователей
     * @return \Inertia\Response
     */
    public function index()
    {        
        return Inertia::render('Users/Index', [
            'filters' => Request::all(['search', 'role']),
            'users' => User::filter(Request::only(['search', 'role']))                
                ->get()
                ->transform($this->transformUser()),
            'roles' => $this->roles(),            
        ]);
    }

    /**
     * Создание пользователя
     * @return \Inertia\Response
     */
    public function create()
    {
        return Inertia::render('Users/Create');
    }

    /**
     * Сохранение нового пользователя
     * @param UserRequest $request
     * @return \Illuminate\Http\RedirectResponse|void
     */
    public function store(UserRequest $request)
    {
        $userFactory = new UserFactory(new LdapFinder(
            host: env('LDAP_SERVER_NAME'),
            port: env('LDAP_SERVER_PORT'),
            dn: env('LDAP_BASE_DN'),
            username: env('LDAP_BIND_USERNAME'),
            password: env('LDAP_BIND_PASSWORD')
        ));
        if ($userFactory->checkUser($request->name) == null) {
            return Session::flash('error', "Пользователь {$request->name} не найден в ЕСК!");
        }
        $user = $userFactory->findOrCreate("$request->name", '.');

        return redirect()->route('users.edit', [$user])
            ->with('success', 'Пользователь успешно создан!');
    }

    /**
     * Редактирование пользователя @param User $user
     * изменение данных пользователя, изменение прав доступа
     * @return \Inertia\Response
     */
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
            //'rolesLabels' => User::rolesLabels(),           
        ]);
    }

    /**
     * Сохранение измененных данных пользователя @param User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(User $user)
    {
        // валидация
        Request::validate([         
            'photo' => ['nullable', 'image'],
        ]);

        // изменение фотографии
        if (Request::file('photo')) {
            $user->update(['photo_path' => Request::file('photo')->store('users')]);
        }
        // изменение прав доступа
        if (Auth::user()->hasRole('admin')) {
            $user->updateRoles(Request::get('selectedRoles'));           
            $user->updateOrganizations(Request::get('selectedOrganizations'));
        }

        return redirect()->back()->with('success', 'Данные пользователя обновлены.');
    }

    /**
     * Удаление пользователя @param User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(User $user)
    {        
        $user->delete();
        return redirect()->back()->with('success', 'Пользователь удален.');
    }

    /**
     * Восстановление удаленного пользователя @param User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function restore(User $user)
    {
        $user->restore();
        return redirect()->back()->with('success', 'Пользователь восстановлен.');
    }    
    
}
