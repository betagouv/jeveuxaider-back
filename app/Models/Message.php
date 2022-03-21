<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Message extends Model
{
    protected $table = 'messages';

    protected $guarded = [
        'id'
    ];

    public function from()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function conversation()
    {
        return $this->belongsTo('App\Models\Conversation');
    }

    protected function content(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => strip_tags($value),
        );
    }
}
