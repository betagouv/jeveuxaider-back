<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserArchivedDatas extends Model
{
    protected $table = 'users_archived_datas';

    protected $fillable = [
        'id', 'email', 'datas'
    ];

    protected $hidden = [ ];

    protected $casts = [
        'datas' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
