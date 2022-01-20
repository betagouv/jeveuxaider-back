<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\MediaCollections\Models\Media as SpatieMedia;

class Media extends SpatieMedia
{
    public function getFormattedMediaField()
    {
        $mediaUrls = ['original' => $this->getFullUrl()];
        foreach ($this->getMediaConversionNames() as $conversion) {
            $mediaUrls[$conversion] = $this->getSrcset($conversion);
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

    // private function getConversions()
    // {
    //     $model = ($this->model_type)::find($this->model_id);
    //     $model->registerMediaConversions();
    //     return array_filter(array_map(function ($conversion) {
    //         return $conversion->shouldBePerformedOn($this->collection_name) ? $conversion->getName() : null;
    //     }, $model->mediaConversions));
    // }
}
