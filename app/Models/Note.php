<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

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

    public function scopeRole($query, $contextRole)
    {
        switch ($contextRole) {
            case 'admin':
                return $query;
            case 'referent':
                return $query
                    ->whereHas('notable', function (Builder $query) {
                        $query->where('department', Auth::guard('api')->user()->departmentsAsReferent->first()->number);
                    });
            default:
                abort(403, 'This action is not authorized');
                break;
        }
    }

    public function getPermissionsAttribute()
    {
        return [
            'canUpdate' =>  auth('api')->user() ? auth('api')->user()->can('update', $this) : false,
            'canDelete' =>  auth('api')->user() ? auth('api')->user()->can('delete', $this) : false,
        ];
    }

}
