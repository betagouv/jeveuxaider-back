<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    protected $table = 'conversations';

    protected $fillable = [
        'participation_id',
    ];

    public function messages()
    {
        return $this->hasMany('App\Models\Message');
    }

    public function users()
    {
        return $this->belongsToMany('App\Models\User', 'conversations_users')->withPivot('read_at');
    }

    public function participation()
    {
        return $this->belongsTo('App\Models\Participation');
    }
}
