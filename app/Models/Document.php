<?php

namespace App\Models;

use App\Models\Media as ModelMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Document extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $table = 'documents';

    protected $guarded = [
        'id',
    ];

    protected $casts = [
        'roles' => 'array',
    ];

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
            case 'responsable_territoire':
            case 'tete_de_reseau':
                return $query->whereJsonContains('roles', 'responsable');
                break;
        }
    }

    public function file()
    {
        return $this->morphOne(ModelMedia::class, 'model')->where('collection_name', 'document__file');
    }
}
