<?php

namespace App\Http\Controllers\Integration;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Laravel\Passport\Exceptions\InvalidAuthTokenException;
use Laravel\Passport\Http\Controllers\DenyAuthorizationController;

class IntegrationDenyAuthorizationController extends DenyAuthorizationController
{
    /**
     * @throws InvalidAuthTokenException
     * @throws \Exception
     */
    public function deny(Request $request)
    {
        $this->assertValidAuthToken($request);

        $authRequest = $this->getAuthRequestFromSession($request);

        $clientUris = Arr::wrap($authRequest->getClient()->getRedirectUri());

        if (! in_array($uri = $authRequest->getRedirectUri(), $clientUris)) {
            $uri = Arr::first($clientUris);
        }
        $shop = explode('?', $request->get('shop', ''))[0];
        $separator = $authRequest->getGrantTypeId() === 'implicit' ? '#'
            : (strstr($uri, '?') ? '&' : '?');

        return $this->response->redirectTo(
            $uri.$separator.'error=access_denied&state='.$request->input('state'). "&shop={$shop}"
        );
    }
}
