<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SocialAccount extends Model
{
    protected $table = 'social_accounts';

    protected $fillable = [
        'provider',
        'provider_user_id',
        'user_id',
        'data',
    ];

    protected $casts = [
        'data' => 'array',
    ];
}
