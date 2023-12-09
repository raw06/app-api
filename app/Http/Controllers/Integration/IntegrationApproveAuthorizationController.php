<?php

namespace App\Http\Controllers\Integration;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Nyholm\Psr7\Response as Psr7Response;
use Laravel\Passport\Exceptions\InvalidAuthTokenException;
use Laravel\Passport\Http\Controllers\ApproveAuthorizationController;

class IntegrationApproveAuthorizationController
    extends ApproveAuthorizationController
{
    /**
     * @throws InvalidAuthTokenException
     * @throws Exception
     */
    public function approve(Request $request): Response
    {
        $this->assertValidAuthToken($request);

        $authRequest = $this->getAuthRequestFromSession($request);
        $response = $this->convertResponse(
            $this->server->completeAuthorizationRequest(
                $authRequest,
                new Psr7Response()
            )
        );
        $location = $response->headers->get('location');
        if ($shop = $request->get('shop')) {
            $location .= "&shop={$shop}";
        }
        return $response->header('location', $location);
    }
}
