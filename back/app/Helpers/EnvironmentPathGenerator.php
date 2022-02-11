<?php

namespace App\Helpers;

use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\Support\PathGenerator\PathGenerator;

// Customize the path where the image gets stored (on the local filesystem, on S3, etc)
class EnvironmentPathGenerator implements PathGenerator
{
    protected $path;

    public function __construct()
    {
        if (env('MEDIA_DISK') == 'media') {
            $this->path = config('filesystems.s3_prefix');
        }
    }

    public function getPath(Media $media): string
    {
        return $this->path . $media->id . "/";
    }

    public function getPathForConversions(Media $media): string
    {
        return $this->getPath($media) . "conversions/";
    }

    public function getPathForResponsiveImages(Media $media): string
    {
        return $this->getPath($media) . "responsive/";
    }
}
