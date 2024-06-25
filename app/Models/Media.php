<?php

namespace App\Models;

use Spatie\MediaLibrary\MediaCollections\Models\Media as SpatieMedia;
use Spatie\MediaLibrary\ResponsiveImages\ResponsiveImage;
use Spatie\MediaLibrary\Support\UrlGenerator\UrlGeneratorFactory;

class Media extends SpatieMedia
{
    protected $appends = ['urls', 'manipulation'];

    protected $hidden = ['model'];

    public function getUrlsAttribute()
    {
        $mediaUrls = ['original' => $this->getUrl()];
        foreach ($this->getConversionsNames() as $conversion) {
            $mediaUrls[$conversion] = $this->hasResponsiveImages($conversion) ? $this->getSrcset($conversion) : $this->getUrl($conversion);
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

    // Override Spatie media method to add url versioning
    public function getSrcset(string $conversionName = ''): string
    {
        $responsiveImages = $this->responsiveImages($conversionName);
        $urlGenerator = UrlGeneratorFactory::createForMedia($this, $conversionName);

        $filesSrcset = $responsiveImages->files
            ->map(fn (ResponsiveImage $responsiveImage) => "{$urlGenerator->versionUrl($responsiveImage->url())} {$responsiveImage->width()}w")
            ->implode(', ');

        $shouldAddPlaceholderSvg = config('media-library.responsive_images.use_tiny_placeholders')
            && $responsiveImages->getPlaceholderSvg();

        if ($shouldAddPlaceholderSvg) {
            $filesSrcset .= ', ' . $responsiveImages->getPlaceholderSvg() . ' 32w';
        }

        return $filesSrcset;
    }
}
