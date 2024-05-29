<?php

namespace App\Models\Auth;

use App\Models\Auth\User;
use App\Models\Organization;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class UserFactory
{

    /**
     * @param LdapFinder $ldapFinder
     */
    public function __construct(private LdapFinder $ldapFinder)
    { }

    public function checkUser(string $username)
    {        
        return $this->ldapFinder->query('(sAMAccountName=' . $username . ')');
    }

    /**
     * @param string $username
     * @param string $domain
     * @return User
     */
    public function findOrCreate(string $username, string $domain): User
    {
        $model = User::query()->where('name', '=', $username)->withTrashed()->first();
        if ($model === null) {
            $model = new User();
            $model->name = $username;
            $model->domain = $domain;
            $model->password = Hash::make(Str::random(16));
        }
        $this->updateUserByLdapInfo($model);
        if ($model->isDirty()) {
            $model->save();
        }
        return $model;
    }

    /**
     * @param \App\Models\User $user
     */
    private function updateUserByLdapInfo(User $user): void
    {
        if (!$this->ldapFinder) {
            return;
        }        
        $data = $this->ldapFinder->query('(sAMAccountName=' . $user->name . ')');
        if (!$data) {                        
            return;
        }
        if ($user->id == null) {            
            $orgCode = $user->getOrgCode();
            if (Organization::find($orgCode) == null) {
                $orgCode = '';
            }
            $user->org_code = $orgCode;
        }
        $user->email = $data->getAttribute('userPrincipalName', false)[0] ?? null;
        $user->fio = $data->getAttribute('cn', false)[0] ?? null;
        $user->post = $data->getAttribute('title', false)[0] ?? null;
        $user->department = $data->getAttribute('department', false)[0] ?? null;
        $user->members = $data->getAttribute('memberOf', false) ?? [];
        $user->lotus_mail = $data->getAttribute('mail', false)[0] ?? null;
        $user->telephone = $data->getAttribute('telephoneNumber', false)[0] ?? null;
        $user->company = $data->getAttribute('company', false)[0] ?? null;
    }

}