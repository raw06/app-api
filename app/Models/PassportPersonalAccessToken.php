<?php

namespace App\Models;

use Laravel\Passport\PersonalAccessClient;

class PassportPersonalAccessToken extends PersonalAccessClient
{
    protected $connection = "mysql_integration";
    protected $table = 'oauth_personal_access_clients';
}
