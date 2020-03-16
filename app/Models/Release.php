<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class Release extends Model
{
    use CrudTrait;

    protected $table = 'releases';

    protected $fillable = [
        'title',
        'date',
        'description',
    ];
}
