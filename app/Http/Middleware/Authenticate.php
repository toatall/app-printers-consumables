<?php

namespace App\Http\Middleware;

use App\Models\UserFactory;
use App\Models\LdapFinder;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Auth;

class Authenticate extends Middleware
{

    public function handle($request, \Closure $next, ...$guards)
    {
        $serverAttr = env('LDAP_SERVER_ATTRIBUTE', 'AUTH_USER');
        $authUser = $request->server->get($serverAttr);
        if ($authUser) {            
            $userFactory = new UserFactory(new LdapFinder(
                host: env('LDAP_SERVER_NAME'),
                port: env('LDAP_SERVER_PORT'),
                dn: env('LDAP_BASE_DN'),
                username: env('LDAP_BIND_USERNAME'),
                password: env('LDAP_BIND_PASSWORD')
            ));
            $user = $userFactory->findOrCreate($authUser);    
            if ($user->deleted_at) {
                return abort(401);
            }    
            Auth::login($user, true);
        }
        else {
            return parent::handle($request, $next, $guards);
        }    
        return $next($request);
    }

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
