<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

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

    public function scopeRole($query, $contextRole = null)
    {
        switch ($contextRole) {
            case 'admin':
                return $query;
                break;
            case 'referent':
            return $query
                    ->whereHas('conversation.participation.mission', function (Builder $query) {
                        $query->where('department', Auth::guard('api')->user()->profile->referent_department);
                    });
                break;
            default:
                return $query->whereHas('conversation.users', function (Builder $subquery) {
                    $subquery->where('users.id', Auth::guard('api')->user()->id);
                });
                break;
        }
    }
}
