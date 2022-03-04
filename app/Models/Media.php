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
        foreach ($this->getConversionsNames() as $conversion) {
            $mediaUrls[$conversion] = $this->getSrcset($conversion) ?: $this->getUrl($conversion);
        }

        return array_filter($mediaUrls);
    }

    public function getManipulationAttribute()
    {
        return !empty($this->manipulations[array_key_first($this->manipulations)]) ?
        $this->manipulations[array_key_first($this->manipulations)] : null;
    }

    private function getConversionsNames()
    {
        $this->load('model');
        $this->model->registerMediaConversions();
        return array_filter(array_map(function ($conversion) {
            return $conversion->shouldBePerformedOn($this->collection_name) ? $conversion->getName() : null;
        }, $this->model->mediaConversions));
    }
}
