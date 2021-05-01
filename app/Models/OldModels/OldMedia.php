<?php

namespace App\Models\OldModels;


use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;
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
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read string $disk_path
 * @property-read string $extension
 * @property-read string $human_readable_size
 * @property-read string $type
 * @property-read Model|\Eloquent $model
 * @method static MediaCollection|static[] all($columns = ['*'])
 * @method static MediaCollection|static[] get($columns = ['*'])
 * @method static Builder|OldMedia newModelQuery()
 * @method static Builder|OldMedia newQuery()
 * @method static Builder|Media ordered()
 * @method static Builder|OldMedia query()
 * @method static Builder|OldMedia whereCollectionName($value)
 * @method static Builder|OldMedia whereConversionsDisk($value)
 * @method static Builder|OldMedia whereCreatedAt($value)
 * @method static Builder|OldMedia whereCustomProperties($value)
 * @method static Builder|OldMedia whereDisk($value)
 * @method static Builder|OldMedia whereFileName($value)
 * @method static Builder|OldMedia whereGeneratedConversions($value)
 * @method static Builder|OldMedia whereId($value)
 * @method static Builder|OldMedia whereManipulations($value)
 * @method static Builder|OldMedia whereMimeType($value)
 * @method static Builder|OldMedia whereModelId($value)
 * @method static Builder|OldMedia whereModelType($value)
 * @method static Builder|OldMedia whereName($value)
 * @method static Builder|OldMedia whereOrderColumn($value)
 * @method static Builder|OldMedia whereResponsiveImages($value)
 * @method static Builder|OldMedia whereSize($value)
 * @method static Builder|OldMedia whereUpdatedAt($value)
 * @method static Builder|OldMedia whereUuid($value)
 * @mixin Eloquent
 * @noinspection PhpFullyQualifiedNameUsageInspection
 * @noinspection PhpUnnecessaryFullyQualifiedNameInspection
 */
class OldMedia extends MediaAlias
{
    protected $connection = 'mysql-old';
    protected $table = 'media';
    protected $primaryKey = 'id';
    public const TYPE_USER = 'App\\User';
    public const TYPE_CATEGORY = 'Modules\\Cms\\Entities\\CategoryTranslation';
    public const TYPE_CONTENT = 'Modules\\Cms\\Entities\\ContentTranslation';
    public const TYPE_DISH = 'Modules\\Jo3aan\\Entities\\Dish';
    public const TYPE_RESTAURANT = 'Modules\\Jo3aan\\Entities\\Restaurant';
    public const COLLECTION_LOGO = 'logo';
    public const COLLECTION_COVER = 'cover';
    public const COLLECTION_GALLERY = 'gallery';

    public function __construct(array $attributes = [])
    {
        $attributes['disk'] = 'old_s3';
        parent::__construct($attributes);
    }


    public function getProductS3Url($id, $fileName): string
    {
        $urlScheme = 'https://tiptop-backend-production.s3.eu-central-1.amazonaws.com/media/dishes/%d/%s';

        return sprintf($urlScheme, $id, $fileName);
    }


    public function getDiskPathAttribute(): string
    {
        $urlScheme = 'media/%s/%d/%s';

        return sprintf($urlScheme, self::getModelTypes()[$this->model_type], $this->id, $this->file_name);
    }

    public function getDiskAttribute(): string
    {
        return 'old_s3';
    }

    public static function getModelTypes(): array
    {
        return [
            self::TYPE_USER => 'users',
            self::TYPE_CATEGORY => 'categoryTranslations',
            self::TYPE_CONTENT => 'contentTranslations',
            self::TYPE_DISH => 'dishes',
            self::TYPE_RESTAURANT => 'restaurants',
        ];
    }

}
