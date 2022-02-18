<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\MediaCollections\Models\Media as SpatieMedia;

class Media extends SpatieMedia
{
    protected $appends = ['urls', 'manipulation'];

    public function getUrlsAttribute()
    {
        $mediaUrls = ['original' => $this->getFullUrl()];
        foreach ($this->getMediaConversionNames() as $conversion) {
            $mediaUrls[$conversion] = $this->getSrcset($conversion);
        }

        return array_filter($mediaUrls);
    }

    public function getManipulationAttribute()
    {
        return !empty($this->manipulations[array_key_first($this->manipulations)]) ?
        $this->manipulations[array_key_first($this->manipulations)] : null;
    }

    public function getFormattedMediaField()
    {
        $mediaUrls = ['original' => $this->getFullUrl()];
        foreach ($this->getMediaConversionNames() as $conversion) {
            $mediaUrls[$conversion] = $this->getSrcset($conversion);
        }

        $manipulations = !empty($this->manipulations[array_key_first($this->manipulations)]) ?
            $this->manipulations[array_key_first($this->manipulations)] : null;

        // name, size, type -> https://developer.mozilla.org/fr/docs/Web/API/File
        return [
            'id' => $this->id,
            'urls' => array_filter($mediaUrls),
            'manipulations' => $manipulations,
            'name' => $this->file_name,
            'size' => $this->size,
            'type' => $this->mime_type
        ];
    }
}
