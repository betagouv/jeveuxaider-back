<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class MessageTemplate extends Model
{
    protected $table = 'message_templates';

    protected $guarded = [
        'id',
    ];

    protected $attributes = [
        'is_shared' => false,
    ];

    protected $casts = [
        'is_shared' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function scopeOfUser($query, $user_id)
    {
        // @TODO is_shared
        $query->where('user_id', $user_id);
    }

}
