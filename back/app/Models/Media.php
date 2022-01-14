<?php

namespace App\Models;

use Spatie\MediaLibrary\MediaCollections\Models\Media as SpatieMedia;

class Media extends SpatieMedia
{
    public function getFormattedMediaField()
    {
        $mediaUrls = ['original' => $this->getFullUrl()];
        foreach ($this->getGeneratedConversions() as $key => $conversion) {
            $mediaUrls[$key] = $this->getUrl($key);
        }

        // name, size, type -> https://developer.mozilla.org/fr/docs/Web/API/File
        return [
            'id' => $this->id,
            'urls' => $mediaUrls,
            'manipulations' => $this->manipulations ? $this->manipulations[array_key_first($this->manipulations)] : null,
            'name' => $this->file_name,
            'size' => $this->size,
            'type' => $this->mime_type
        ];
    }
}
