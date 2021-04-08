<?php

namespace App\Models\OldModels;


use Spatie\MediaLibrary\MediaCollections\Models\Media as MediaAlias;


/**
 * App\Models\OldModels\OldMedia
 *
 * @property int $id
 * @property string $model_type
 * @property int $model_id
 * @property string|null $uuid
 * @property string $collection_name
 * @property string $name
 * @property string $file_name
 * @property string|null $mime_type
 * @property string $disk
 * @property string|null $conversions_disk
 * @property int $size
 * @property array $manipulations
 * @property array $custom_properties
 * @property array $generated_conversions
 * @property array $responsive_images
 * @property int|null $order_column
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read string $extension
 * @property-read string $human_readable_size
 * @property-read string $type
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $model
 * @method static \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection|static[] all($columns = ['*'])
 * @method static \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection|static[] get($columns = ['*'])
 * @method static \Illuminate\Database\Eloquent\Builder|OldMedia newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OldMedia newQuery()
 * @method static Builder|Media ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|OldMedia query()
 * @method static \Illuminate\Database\Eloquent\Builder|OldMedia whereCollectionName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OldMedia whereConversionsDisk($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OldMedia whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OldMedia whereCustomProperties($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OldMedia whereDisk($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OldMedia whereFileName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OldMedia whereGeneratedConversions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OldMedia whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OldMedia whereManipulations($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OldMedia whereMimeType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OldMedia whereModelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OldMedia whereModelType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OldMedia whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OldMedia whereOrderColumn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OldMedia whereResponsiveImages($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OldMedia whereSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OldMedia whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OldMedia whereUuid($value)
 * @mixin \Eloquent
 */
class OldMedia extends MediaAlias
{
    protected $connection = 'mysql-old';
    protected $table = 'media';
    protected $primaryKey = 'id';


    public function getProductS3Url($id, $fileName): string
    {
        $urlScheme = 'https://tiptop-backend-production.s3.eu-central-1.amazonaws.com/media/dishes/%d/%s';

        return sprintf($urlScheme, $id, $fileName);
    }

}
