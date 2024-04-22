<?php

namespace App\Models\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class UserChecker
{
    /**
     * @var \App\Models\Auth\LdapFinder
     */
    private LdapFinder $ldapFinder;

    /**
     * @param LdapFinder $ldapFinder
     */
    public function __construct(LdapFinder $ldapFinder)
    {
        $this->ldapFinder = $ldapFinder;
    }

    public function checkUser(string $username)
    {
        return $this->ldapFinder->query('(sAMAccountName=' . $username . ')');
    }

    /**
     * @param string $usernameAttr
     * @return User
     */
    public function findOrCreate(string $usernameAttr): User
    {
        $userFullName = explode('\\', $usernameAttr);
        $userName = $userFullName[1] ?? $userFullName[0];
        $domain = $userFullName[0] ?? '.';
        
        $model = User::query()->where('name', $userName)->withTrashed()->first();
        if ($model?->deleted_at) {
            return $model;
        }
        if ($model === null) {
            $model = new User();
            $model->name = $userName;
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
        $user->org_code = $user->getOrgCode();        
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