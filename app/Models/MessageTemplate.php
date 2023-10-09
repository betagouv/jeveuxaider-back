<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

class MessageTemplate extends Model
{
    protected $table = 'message_templates';

    protected $fillable = [
        'name',
        'is_shared',
        'message',
        'user_id',
        'sharable_id',
        'sharable_type',
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

    public function sharable()
    {
        return $this->morphTo();
    }

    public function scopeOfUser($query, $user_id)
    {
        $query->where('user_id', $user_id);
    }

    public function scopeRole($query, $contextRole)
    {
        switch ($contextRole) {
            case 'responsable':
                return $query->where('user_id', Auth::guard('api')->user()->id)
                    ->orWhere(function (Builder $query) {
                        $query->where('is_shared', true)
                            ->where(function (Builder $query) {
                                $query->where('sharable_id', Auth::guard('api')->user()->contextable_id)
                                ->where('sharable_type', "App\Models\Structure");
                            });
                    });
                break;
            default:
                return  $query->where('user_id', Auth::guard('api')->user()->id);
        }
    }

}
