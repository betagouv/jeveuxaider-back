<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasCompositePrimaryKey;

class Rolable extends Model
{
    use HasCompositePrimaryKey;

    protected $table = 'rolables';
    protected $primaryKey = ['role_id', 'user_id', 'rolable_type', 'rolable_id'];
    public $timestamps = false;
    public $incrementing = false;
}
