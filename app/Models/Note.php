<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{

    protected $table = 'notes';

    protected $guarded = ['id', 'created_at'];

    protected $appends = ['permissions'];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function notable()
    {
        return $this->morphTo();
    }

    public function getPermissionsAttribute()
    {
        return [
            'canUpdate' =>  auth('api')->user() ? auth('api')->user()->can('update', $this) : false,
            'canDelete' =>  auth('api')->user() ? auth('api')->user()->can('delete', $this) : false,
        ];
    }

}
