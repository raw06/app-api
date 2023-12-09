<?php

namespace App\Repositories\PassportAccessToken;

use App\Bridge\PassportAccessToken;
use Laravel\Passport\Bridge\AccessTokenRepository;
use League\OAuth2\Server\Entities\ClientEntityInterface;

class PassportAccessTokenRepository extends AccessTokenRepository
{
    public function getNewToken(
        ClientEntityInterface $clientEntity,
        array $scopes,
        $userIdentifier = null
    ): PassportAccessToken {
        return new PassportAccessToken(
            $userIdentifier, $scopes, $clientEntity
        );
    }
}
