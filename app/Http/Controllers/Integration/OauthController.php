<?php

namespace App\Http\Controllers\Integration;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Laravel\Passport\Bridge\User;
use Laravel\Passport\ClientRepository;
use Laravel\Passport\Exceptions\OAuthServerException;
use Laravel\Passport\Http\Controllers\AuthorizationController;
use Laravel\Passport\TokenRepository;
use Psr\Http\Message\ServerRequestInterface;
use Nyholm\Psr7\Response as Psr7Response;



class OauthController extends AuthorizationController
{
    private $shopDomain;
    /**
     * @throws OAuthServerException
     */
    public function authorize(
        ServerRequestInterface $psrRequest,
        Request $request,
        ClientRepository $clients,
        TokenRepository $tokens
    ): \Illuminate\Http\Response {
        $tokensApp = new \App\Repositories\Tokens\TokenRepository();
        $authRequest = $this->withErrorHandling(function () use ($psrRequest) {
            return $this->server->validateAuthorizationRequest($psrRequest);
        });
        $scopes = $this->parseScopes($authRequest);

        $token = $tokensApp->findValidToken(
            $user = $request->user(),
            $client = $clients->find($authRequest->getClient()->getIdentifier())
        );
        // remove token expire
        if(!$token) {
            $user = $request->user();
            $client = $clients->find($authRequest->getClient()->getIdentifier());
            $tokenInValid = $tokensApp->findInValidTokens($user,$client);
            if($tokenInValid) {
                $tokenInValid->refreshToken()->first()->delete();
                $tokenInValid->delete();
                $client->authCodes()->first()->delete();
            }
        }
        $shopDomain = $request->get('shop_domain', '');
        if (($token && $token->scopes === collect($scopes)->pluck('id')->all())
            || $client->skipsAuthorization()
        ) {
            $this->shopDomain = $shopDomain;
            return $this->approveRequest($authRequest, $user);
        }
        $request->session()->put('authToken', $authToken = Str::random());

        $request->session()->put('authRequest', $authRequest);

        return $this->response->view('integration.authorize', [
            'client'    => $client,
            'user'      => $user,
            'scopes'    => $scopes,
            'request'   => $request,
            'authToken' => $authToken,
            'shop'      => $shopDomain
        ]);
    }

    /**
     * Approve the authorization request.
     *
     * @param \League\OAuth2\Server\RequestTypes\AuthorizationRequest $authRequest
     * @param \Illuminate\Database\Eloquent\Model                     $user
     *
     * @return \Illuminate\Http\Response
     * @throws OAuthServerException
     */
    public function approveRequest($authRequest, $user)
    {
        $authRequest->setUser(new User($user->getAuthIdentifier()));

        $authRequest->setAuthorizationApproved(true);

        $responseContent = $this->convertResponse(
            $this->server->completeAuthorizationRequest(
                $authRequest,
                new Psr7Response()
            )
        );

        $location = $responseContent->headers->get('location');

        if ($this->shopDomain) {
            $location .= "&shop={$this->shopDomain}";
        }
        return $this->withErrorHandling(
            function () use ($authRequest, $responseContent, $location) {
                return $responseContent->header('location', $location);
            }
        );
    }
}
