<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Maize\Encryptable\Encryptable;

class UserArchivedDatas extends Model
{
    protected $table = 'users_archived_datas';

    protected $fillable = [
        'id', 'email', 'datas'
    ];

    protected $hidden = [ ];

    protected $casts = [
        'email' => Encryptable::class,
        'datas' => Encryptable::class,
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
