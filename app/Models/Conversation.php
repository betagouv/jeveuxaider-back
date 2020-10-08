<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

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


    public function scopeRole($query)
    {
        return $query->whereHas('users', function (Builder $subquery) {
            $subquery->where('users.id', Auth::guard('api')->user()->id);
        });
    }
}
