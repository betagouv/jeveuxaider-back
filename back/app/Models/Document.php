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
        'type',
        'link'
    ];

    protected $casts = [
        'roles' => 'array'
    ];

    protected $appends = ['file'];

    protected $hidden = ['media'];

    public function getFileAttribute()
    {
        return $this->getMedia('documents', ['attribute' => 'file'])->map(function ($media) {
            return $media->getFormattedMediaField();
        });
    }

    public function scopeRole($query, $contextRole)
    {
        switch ($contextRole) {
            case 'admin':
                return $query;
                break;
            case 'referent':
            case 'referent_regional':
                return $query->whereJsonContains('roles', 'referent');
                break;
            case 'responsable':
                return $query->whereJsonContains('roles', 'responsable');
                break;
        }
    }
}
