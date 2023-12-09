<?php

namespace App\Providers;

use Illuminate\Contracts\Container\BindingResolutionException;
use League\OAuth2\Server\AuthorizationServer;

class PassportServiceProvider extends \Laravel\Passport\PassportServiceProvider
{
    /**
     * @throws BindingResolutionException
     */
    public function makeAuthorizationServer(): AuthorizationServer
    {
        return new AuthorizationServer(
            $this->app->make(\Laravel\Passport\Bridge\ClientRepository::class),
            $this->app->make(
                \App\Repositories\PassportAccessToken\PassportAccessTokenRepository::class
            ),
            $this->app->make(\Laravel\Passport\Bridge\ScopeRepository::class),
            $this->makeCryptKey('private'),
            app('encrypter')->getKey()
        );
    }

}
