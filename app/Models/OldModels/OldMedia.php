<?php

namespace App\Models\OldModels;


use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;
use Spatie\MediaLibrary\MediaCollections\Models\Media as MediaAlias;
use Storage;
use Str;


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
 * @property-read string|null $image_url
 * @property-read string $resized_disk_path
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
        $urlScheme = 'https://'.env('OLD_AWS_BUCKET').'.s3.eu-central-1.amazonaws.com/media/dishes/%d/%s';

        return sprintf($urlScheme, $id, $fileName);
    }


    public function getDiskPathAttribute(): string
    {
        $urlScheme = 'media/%s/%d/%s';

        return sprintf($urlScheme, self::getModelTypes()[$this->model_type], $this->id, $this->file_name);
    }


    public function getResizedDiskPathAttribute($conversion): string
    {
        $urlScheme = 'media/%s/%d/resized/%s';
        $extension = $this->getExtensionAttribute();
        $resizedFileName = Str::of($this->file_name)->beforeLast('.')->append('-')
                               ->append($conversion.'.'.$extension)->jsonSerialize();

        return sprintf($urlScheme, self::getModelTypes()[$this->model_type], $this->id, $resizedFileName);
    }


    public function getImageUrlAttribute(): ?string
    {
        if ( ! Storage::disk('old_s3')->exists($this->disk_path)) {

            $urlScheme = 'https://'.env('OLD_AWS_BUCKET').'.s3.eu-central-1.amazonaws.com/media/%s/%d/resized/%s';
            $extension = $this->getExtensionAttribute();
            $lastGeneratedConversion = $this->getGeneratedConversions()->keys()->last();
            $resizedDiskPathAttribute = $this->getResizedDiskPathAttribute($lastGeneratedConversion);
//            if($this->id == 8767){
//                dd($resizedDiskPathAttribute);
//            }


            $seemsNull = false;
            if ( ! Storage::disk('old_s3')->exists($resizedDiskPathAttribute)) {
                $seemsNull = true;
            }

            if ($seemsNull) {
                $resizedDiskPathAttribute = str_replace('.png', '.jpg', $resizedDiskPathAttribute);
                if (Storage::disk('old_s3')->exists($resizedDiskPathAttribute)) {
                    $extension = 'jpg';
                    $seemsNull = false;
                }
            }

            if ($seemsNull) {
                $resizedDiskPathAttribute = str_replace('.jpeg', '.jpg', $resizedDiskPathAttribute);
                if (Storage::disk('old_s3')->exists($resizedDiskPathAttribute)) {
                    $extension = 'jpg';
                    $seemsNull = false;
                }
            }

            $this->encodeFileName();

            $finalUrl = sprintf($urlScheme, self::getModelTypes()[$this->model_type], $this->id, $this->file_name);


            if ($seemsNull) {
                return null;
            }

            return Str::of($finalUrl)->beforeLast('.')
                       ->append('-')
                       ->append($lastGeneratedConversion)
                       ->append('.'.$extension)
                       ->jsonSerialize();
        }

        $urlScheme = 'https://'.env('OLD_AWS_BUCKET').'.s3.eu-central-1.amazonaws.com/media/%s/%d/%s';

        $this->encodeFileName();

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

    private function encodeFileName(): void
    {
        if (str_contains($this->file_name, '+')) {
            $this->file_name = str_replace('+', '%2B', $this->file_name);
        }
        $this->file_name = preg_replace_callback('/[اأإء-ي]/ui', function ($m) {
            return urlencode($m[0]);
        }, $this->file_name);

        $this->file_name = preg_replace_callback('/[٠-٩]/ui', function ($m) {
            return urlencode($m[0]);
        }, $this->file_name);
        /*
         * With these included:
         گە
        ی
         * */
        $this->file_name = str_replace('گ', '%DA%AF', $this->file_name);
        $this->file_name = str_replace('ی', '%DB%8C', $this->file_name);
        $this->file_name = str_replace('،', '%D8%8C', $this->file_name);
        $this->file_name = str_replace('ە', '%DB%95', $this->file_name);
    }

}
