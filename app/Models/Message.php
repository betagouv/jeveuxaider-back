<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $table = 'messages';

    protected $fillable = [
        'content',
        'conversation_id',
        'type'
    ];

    public function from()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function conversation()
    {
        return $this->belongsTo('App\Models\Conversation');
    }
}
