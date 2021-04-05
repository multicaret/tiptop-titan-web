<?php

namespace App\Utilities;

use Illuminate\Support\Str;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\Support\PathGenerator\PathGenerator;

class CustomPathGenerator implements PathGenerator
{
    public function getPath(Media $media): string
    {
        return sprintf('uploads/%s/%d/', lcfirst(Str::plural(explode('\\', $media->model_type)[2])), $media->id);
    }

    public function getPathForConversions(Media $media): string
    {
        return $this->getPath($media).'resized/';
    }

    public function getPathForResponsiveImages(Media $media): string
    {
        return $this->getPath($media).'/resp/';
    }
}
