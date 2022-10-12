<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

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

    public function latestMessage()
    {
        return $this->hasOne('App\Models\Message')->where('type', 'chat')->latest('id');
    }

    public function users()
    {
        return $this->belongsToMany('App\Models\User', 'conversations_users')->withPivot('read_at', 'status');
    }

    public function conversable()
    {
        return $this->morphTo();
    }

    public function scopeRole($query, $contextRole = null)
    {
        switch ($contextRole) {
            case 'admin':
                return $query;
                break;
            default:
                return $query->whereHas('users', function (Builder $subquery) {
                    $subquery->where('users.id', Auth::guard('api')->user()->id);
                });
                break;
        }
    }

    public function scopeWithUsers($query, $users)
    {
        $users = explode(',', $users);

        return $query->whereHas('users', function (Builder $query) use ($users) {
            foreach ($users as $userId) {
                $query->where('users.id', $userId);
            }
        });
    }

    public function setResponseTime()
    {
        if ($this->response_time) {
            return $this;
        }

        $this->response_time = time() - $this->created_at->timestamp;

        return $this;
    }

    // TEMP LARAVEL 7. DISPO DANS LARAVEL 8
    // public function saveQuietly(array $options = [])
    // {
    //     return static::withoutEvents(function () use ($options) {
    //         return $this->save($options);
    //     });
    // }
}
