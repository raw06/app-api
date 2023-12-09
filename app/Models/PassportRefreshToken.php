<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\RefreshToken;

class PassportRefreshToken extends RefreshToken
{
    protected $connection = "mysql_integration";
    protected $table = 'oauth_refresh_tokens';
}
