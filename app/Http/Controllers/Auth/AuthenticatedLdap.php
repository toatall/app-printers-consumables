<?php
namespace App\Http\Controllers\Auth;

use App\Models\Auth\LdapFinder;
use App\Models\Auth\UserFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticatedLdap
{

    /**
     * Ldap аутентификация
     * @param Request $request
     * @return bool
     */
    public function login(Request $request): bool
    {
        $serverAttr = env('LDAP_SERVER_ATTRIBUTE', 'AUTH_USER');
        $authUser = $request->server->get($serverAttr);
        if (!empty($authUser)) {
            $ldapFinder = new LdapFinder(
                host: env('LDAP_SERVER_NAME'),
                port: env('LDAP_SERVER_PORT'),
                dn: env('LDAP_BASE_DN'),
                username: env('LDAP_BIND_USERNAME'),
                password: env('LDAP_BIND_PASSWORD'),
            );

            list($username, $domain) = $this->splitFullUsername($authUser);

            $userFactory = new UserFactory($ldapFinder);
            $userModel = $userFactory->findOrCreate($username, $domain);            
            if ($userModel->deleted_at) {
                return abort(401);
            }
            Auth::login($userModel, true);
            return true;
        }        
        return false;
    }

    /**
     * Выделение имени пользователя и домена из полного имени пользователя
     * @param string $fullUsername полное имя пользователя с доменом
     * @return array
     */
    private function splitFullUsername(string $fullUsername): array
    {
        $splitUsername = explode('\\', $fullUsername);
        return [
            $splitUsername[1] ?? '',
            $splitUsername[0] ?? '.',
        ];        
    }
}