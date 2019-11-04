<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
//            return route('login');
            /**
             * redirect to SSO login URL instead of Laravel default URL
             */
            return route(
                'saml2_login',
                [
                    'idpName' => 'RENTAL'
                ]
            );
        }
    }
}
