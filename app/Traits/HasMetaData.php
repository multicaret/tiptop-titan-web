<?php

namespace App\Traits;

use App\Models\MetaData;
use Illuminate\Database\Eloquent\Relations\MorphOne;

trait HasMetaData
{
    /**
     * The "booting" method of the trait.
     *
     * @return void
     */
    public static function bootHasMetaData()
    {
        static::saved(function ($model) {
            $model->saveMetaData(request('meta', []));
        });
    }

    /**
     * Get the meta for the model.
     *
     * @return MorphOne
     */
    public function meta(): MorphOne
    {
        return $this->morphOne(MetaData::class, 'model')->withDefault();
    }

    /**
     * Save meta data for the model.
     *
     * @param  array  $data
     * @return void
     */
    public function saveMetaData($data = []): void
    {
        $data = collect($data)->filter(function ($item, $key) {
            return ! $this->containsOnlyNull($item);
        })->toArray();
        $this->meta->fill($data)->save();
    }

    private function containsOnlyNull($input): bool
    {
        return empty(array_filter($input, function ($a) {
            return $a !== null;
        }));
    }
}
