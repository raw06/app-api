<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if ($request->is('oauth/authorize')) {
            if (!auth()->check()) {
                $authorizeUrl = $request->fullUrl();
                if ($authorizeUrl
                    && $shopDomain = $request->get('shop_domain')
                ) {
                    return route(
                        'integration.login',
                        [
                            'shop_domain' => $shopDomain,
                            'authorize_url' => $authorizeUrl
                        ]
                    );
                }
            }
        }
        if (! $request->expectsJson()) {
            return route('login');
        }
    }
}
