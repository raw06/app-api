<?php

namespace App\Repositories\Tokens;

use App\Models\PassportToken;
use Carbon\Carbon;

class TokenRepository extends \Laravel\Passport\TokenRepository {

    /**
     * Find a valid token for the given user and client.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $user
     * @param  \Laravel\Passport\Client  $client
     * @return PassportToken |null
     */
    public function findInValidTokens($user, $client)
    {
        return $client->tokens()
            ->whereUserId($user->getAuthIdentifier())
            ->where('revoked', 0)
            ->where('expires_at', '<=', Carbon::now())->first();
    }
}
