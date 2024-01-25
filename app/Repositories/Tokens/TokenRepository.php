<?php

namespace App\Repositories\Tokens;

use App\Models\PassportToken;
use App\Models\User;
use Carbon\Carbon;
use Laravel\Passport\Client;

class TokenRepository extends \Laravel\Passport\TokenRepository {

    /**
     * Find a valid token for the given user and client.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $user
     * @param  \Laravel\Passport\Client  $client
     * @return PassportToken |null
     */
    public function findInValidTokens(User $user, Client $client)
    {
        return $client->tokens()
            ->whereUserId($user->getAuthIdentifier())
            ->where('revoked', 0)
            ->where('expires_at', '<=', Carbon::now())->first();
    }
}
