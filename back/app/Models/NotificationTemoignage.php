<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class NotificationTemoignage extends Model
{
    protected $table = 'notifications_temoignages';
    protected $casts = [
        'last_sent_at' => 'datetime'
    ];
    protected $fillable = [
        'participation_id',
        'token',
        'reminders_sent',
        'last_sent_at',
    ];

    public function participation()
    {
        return $this->belongsTo('App\Models\Participation');
    }
}
