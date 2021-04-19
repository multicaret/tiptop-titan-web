<?php


namespace App\Traits;


use Illuminate\Support\Arr;
use Spatie\MediaLibrary\InteractsWithMedia;

trait HasMediaTrait
{
    use InteractsWithMedia;

    public function getMediaForUploader($collection, $mainFileConversion = '')
    {
        $items = [];

        if ($this->hasMedia($collection)) {
            $items = $this->getMedia($collection)
                          ->sortBy(function ($media, $key) {
                              return $media->order_column;
                          })
                /*->sortBy(function ($media, $key) {
                    return $media->getCustomProperty('order');
                })*/
                          ->map(function ($media) use ($mainFileConversion) {
                    return [
                        'id' => $media->id,
                        'name' => $media->name,
                        'type' => $media->mime_type,
                        'size' => $media->size,
                        'file' => $media->getFullUrl($mainFileConversion),
                        'thumbnail' => Arr::has($media->getGeneratedConversions()->keys(), 'HD')? $media->getFullUrl('HD'): '',
                        'data' => [
                            'extension' => explode('/', $media->mime_type)[1],
                            'listProps' => [
                                'id' => $media->id
                            ],
                        ]
                    ];
                })
                          ->toArray();
            $items = array_values($items);
        }

        return $items;
    }
}
