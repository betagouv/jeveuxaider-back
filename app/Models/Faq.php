<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class Faq extends Model
{
    use CrudTrait;

    protected $table = 'faqs';

    protected $fillable = [
        'title',
        'weight',
        'description',
    ];
}
