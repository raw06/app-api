<?php

namespace App\Http\Controllers\Integration;

use Nyholm\Psr7\Response as Psr7Response;
use Laravel\Passport\Exceptions\OAuthServerException;
use Laravel\Passport\Http\Controllers\AccessTokenController;
use Psr\Http\Message\ServerRequestInterface;

class IntegrationAccessTokenController extends AccessTokenController
{
    /**
     * @throws OAuthServerException
     * @throws \League\OAuth2\Server\Exception\OAuthServerException
     */
    public function issueToken(ServerRequestInterface $request)
    {
        $responseContent = $this->convertResponse(
            $this->server->respondToAccessTokenRequest(
                $request,
                new Psr7Response()
            )
        );
        $shopDomain = collect($request->getQueryParams())->get('shop_domain') ??
            collect($request->getQueryParams())->get('shop');
        $location = $responseContent->headers->get('location');

        if ($shopDomain) {
            $location .= "&shop={$shopDomain}";
        }
        return $this->withErrorHandling(
            function () use ($responseContent, $location) {
                return $responseContent->header('location', $location);
            }
        );
    }
}
