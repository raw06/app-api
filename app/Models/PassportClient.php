<?php

namespace App\Models;

use Laravel\Passport\Client;

class PassportClient extends Client
{
    protected $connection = "mysql_integration";
    protected $table = 'oauth_clients';
}
