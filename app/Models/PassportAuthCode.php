<?php

namespace App\Models;

use Laravel\Passport\AuthCode;

class PassportAuthCode extends AuthCode
{
    protected $connection = "mysql_integration";
    protected $table = 'oauth_auth_codes';
}
