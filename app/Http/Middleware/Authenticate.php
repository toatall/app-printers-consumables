<?php

namespace App\Http\Middleware;

use App\Models\Auth\UserFactory;
use App\Models\Auth\LdapFinder;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Auth;

class Authenticate extends Middleware
{

    // /**
    //  * {@inheritDoc}
    //  */
    // public function handle($request, \Closure $next, ...$guards)
    // {
    //     $serverAttr = env('LDAP_SERVER_ATTRIBUTE', 'AUTH_USER');
    //     $authUser = $request->server->get($serverAttr);
    //     if ($authUser) { 
           
    //         $ldapFinder = new LdapFinder(
    //             host: env('LDAP_SERVER_NAME'),
    //             port: env('LDAP_SERVER_PORT'),
    //             dn: env('LDAP_BASE_DN'),
    //             username: env('LDAP_BIND_USERNAME'),
    //             password: env('LDAP_BIND_PASSWORD'),
    //         );

    //         list($username, $domain) = $this->splitFullUsername($authUser);

    //         $userFactory = new UserFactory($ldapFinder);
    //         $userModel = $userFactory->findOrCreate($username, $domain);            
    //         if ($userModel->deleted_at) {
    //             return abort(401);
    //         }
    //         Auth::login($userModel, true);
    //     }
    //     else {
    //         return parent::handle($request, $next, $guards);
    //     }    
    //     return $next($request);
    // }

    // /**
    //  * @param string $fullUsername
    //  * @return array
    //  */
    // private function splitFullUsername(string $fullUsername): array
    // {
    //     $splitUsername = explode('\\', $fullUsername);
    //     return [
    //         $splitUsername[1] ?? '',
    //         $splitUsername[0] ?? '.',
    //     ];        
    // }


    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            return route('login');
        }
    }
}
