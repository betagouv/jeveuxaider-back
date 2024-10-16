<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MissionUserFavorite extends Model
{
    use HasFactory;

    protected $table = 'missions_users_favorites';

    protected $guarded = [
        'id',
    ];

    public function mission()
    {
        return $this->belongsTo('App\Models\Mission');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function scopeOfUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
}
