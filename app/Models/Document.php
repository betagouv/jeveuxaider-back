<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Document extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $table = 'documents';

    protected $fillable = [
        'title',
        'file',
        'description',
        'roles',
    ];

    protected $casts = [
        'roles' => 'array'
    ];

    protected $appends = ['file'];

    protected $hidden = ['media'];

    public function getFileAttribute()
    {
        $file = null;
        $file = $this->getFirstMedia('documents');
        if ($file) {
            $file->url = $file->getFullUrl();
        }
        return $file;
    }
}
