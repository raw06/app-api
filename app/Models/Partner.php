<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    use HasFactory;
    protected $connection ="mysql_integration";
    protected $table="oauth_clients";

    protected $fillable = [
        'id',
        'name',
        'youtube_link',
        'description_image',
        'doc_link',
        'rick_text',
        'back_link',
        'description',
        'app_logo',
        'app_link'
    ];

    protected $casts = [
        'id' => 'string'
    ];
}
