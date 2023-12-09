<?php

namespace App\Models;

use Laravel\Passport\Token;

class PassportToken extends Token
{
    protected $connection = "mysql_integration";
    protected $table = 'oauth_access_tokens';

    public function refreshToken()
    {
        return $this->hasOne(PassportRefreshToken::class, 'access_token_id', 'id')->first();
    }
}
