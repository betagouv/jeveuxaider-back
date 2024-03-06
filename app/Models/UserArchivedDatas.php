<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Maize\Encryptable\Encryptable;

class UserArchivedDatas extends Model
{
    protected $table = 'users_archived_datas';

    protected $fillable = [
        'id', 'email', 'datas', 'code'
    ];

    protected $hidden = [ ];

    protected $casts = [
        'email' => Encryptable::class,
        'datas' => Encryptable::class,
        'code' => Encryptable::class,
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function generateNewCode()
    {
        $this->code = random_int(100000, 999999);
        $this->save();
    }
}
