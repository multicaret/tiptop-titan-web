<?php

namespace App\Models\OldModels;


use App\Models\Region;
use App\Models\User;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Str;

/**
 * App\Models\OldModels\OldUser
 *
 * @property int $id
 * @property int|null $branch_id
 * @property string|null $type
 * @property string|null $sub_type
 * @property string|null $name
 * @property string|null $username
 * @property string|null $phone_number
 * @property string|null $email
 * @property string|null $password
 * @property string|null $image
 * @property string|null $verification_code VERIFIED: Verified | HASH: Needs Verification
 * @property string|null $confirmation_code
 * @property int $send_code_attempts
 * @property string|null $send_code_again_date
 * @property int $submit_code_attempts
 * @property string|null $submit_code_again_date
 * @property string $sex MALE|FEMALE|NOT_SPECIFIED
 * @property string|null $birth_date
 * @property string $status PENDING | ACTIVE | SUSPENDED | FROZEN
 * @property int|null $country_id
 * @property int|null $city_id
 * @property string $registration_type NORMAL, FACEBOOK, TWITTER, GOOGLE, INTAGRAM
 * @property string|null $social_id
 * @property int|null $online
 * @property string|null $remember_token
 * @property string|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int $receive_fcm_notification
 * @property string|null $notification_mode
 * @property string|null $workiom_id
 * @property string|null $latitude
 * @property string|null $longitude
 * @property string|null $zoho_id
 * @property string|null $agent_device
 * @property string|null $agent_os
 * @property string|null $locale
 * @property mixed|null $tokens
 * @property int $orders_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\OldModels\OldFirebaseUsersTokens[] $firebaseUser
 * @property-read int|null $firebase_user_count
 * @property-read \App\Models\OldModels\OldFirebaseUsersTokens|null $firebase_data
 * @property-read string $first_name
 * @property-read string $last_name
 * @property-read array $mobile_app_details
 * @property-read mixed $new_city_id
 * @property-read mixed $new_country_id
 * @property-read mixed $region_id
 * @property-read mixed $role_name
 * @property-read mixed $settings
 * @property-read string|null $tel_code_number
 * @property-read string|null $tel_number
 * @property-read string $updated_username
 * @property-read \App\Models\OldModels\OldRegion|null $region
 * @method static Builder|OldUser newModelQuery()
 * @method static Builder|OldUser newQuery()
 * @method static Builder|OldUser query()
 * @method static Builder|OldUser whereAgentDevice($value)
 * @method static Builder|OldUser whereAgentOs($value)
 * @method static Builder|OldUser whereBirthDate($value)
 * @method static Builder|OldUser whereBranchId($value)
 * @method static Builder|OldUser whereCityId($value)
 * @method static Builder|OldUser whereConfirmationCode($value)
 * @method static Builder|OldUser whereCountryId($value)
 * @method static Builder|OldUser whereCreatedAt($value)
 * @method static Builder|OldUser whereDeletedAt($value)
 * @method static Builder|OldUser whereEmail($value)
 * @method static Builder|OldUser whereId($value)
 * @method static Builder|OldUser whereImage($value)
 * @method static Builder|OldUser whereLatitude($value)
 * @method static Builder|OldUser whereLocale($value)
 * @method static Builder|OldUser whereLongitude($value)
 * @method static Builder|OldUser whereName($value)
 * @method static Builder|OldUser whereNotificationMode($value)
 * @method static Builder|OldUser whereOnline($value)
 * @method static Builder|OldUser whereOrdersCount($value)
 * @method static Builder|OldUser wherePassword($value)
 * @method static Builder|OldUser wherePhoneNumber($value)
 * @method static Builder|OldUser whereReceiveFcmNotification($value)
 * @method static Builder|OldUser whereRegistrationType($value)
 * @method static Builder|OldUser whereRememberToken($value)
 * @method static Builder|OldUser whereSendCodeAgainDate($value)
 * @method static Builder|OldUser whereSendCodeAttempts($value)
 * @method static Builder|OldUser whereSex($value)
 * @method static Builder|OldUser whereSocialId($value)
 * @method static Builder|OldUser whereStatus($value)
 * @method static Builder|OldUser whereSubType($value)
 * @method static Builder|OldUser whereSubmitCodeAgainDate($value)
 * @method static Builder|OldUser whereSubmitCodeAttempts($value)
 * @method static Builder|OldUser whereTokens($value)
 * @method static Builder|OldUser whereType($value)
 * @method static Builder|OldUser whereUpdatedAt($value)
 * @method static Builder|OldUser whereUsername($value)
 * @method static Builder|OldUser whereVerificationCode($value)
 * @method static Builder|OldUser whereWorkiomId($value)
 * @method static Builder|OldUser whereZohoId($value)
 * @mixin Eloquent
 * @noinspection PhpFullyQualifiedNameUsageInspection
 * @noinspection PhpUnnecessaryFullyQualifiedNameInspection
 */
class OldUser extends OldModel
{
    protected $table = 'users';
    protected $primaryKey = 'id';


    public const STATUS_ACTIVE = 'ACTIVE';
    public const STATUS_PENDING = 'PENDING';
    public const STATUS_SUSPENDED = 'SUSPENDED';

    public function attributesComparing(): array
    {
        $attributesKeys = [
            'id' => 'id',
            'updated_username' => 'username',
            'email' => 'email',
            'password' => 'password',
            'birth_date' => 'dob',
            'first_name' => 'first',
            'last_name' => 'last',
            'tel_code_number' => 'phone_country_code',
            'tel_number' => 'phone_number',
            'new_country_id' => 'country_id',
            'region_id' => 'region_id',
            'new_city_id' => 'city_id',
            'orders_count' => 'total_number_of_orders',
            'settings' => 'settings',
            'created_at' => 'created_at',
            'updated_at' => 'updated_at',

        ];

        if (self::validateLatLong($this->latitude, $this->longitude)) {
            $attributesKeys = array_merge($attributesKeys, [
                'latitude' => 'latitude',
                'longitude' => 'longitude'
            ]);
        }

        return $attributesKeys;
    }

    public static function roleComparing(): array
    {
        return [
            'ROOT' => User::ROLE_SUPER,
            'SUPERADMIN' => User::ROLE_ADMIN,
            'ADMIN' => User::ROLE_AGENT,
            'MARKETER' => User::ROLE_MARKETER,
            'RESTAURANT_SUPERADMIN' => User::ROLE_BRANCH_OWNER,
            'RESTAURANT_ADMIN' => User::ROLE_BRANCH_MANAGER,
            'RESTAURANT_REPRESENTATIVE' => User::ROLE_BRANCH_MANAGER,
            'RESTAURANT_DRIVER' => User::ROLE_RESTAURANT_DRIVER,
            'APP_DRIVER' => User::ROLE_TIPTOP_DRIVER,
            // Todo: ask MK
            'DRIVER' => User::ROLE_TIPTOP_DRIVER,
            'CUSTOMER' => User::ROLE_USER,
            '' => User::ROLE_USER,
            null => User::ROLE_USER,
//            '' => User::ROLE_SUPERVISOR,
//            '' => User::ROLE_CONTENT_EDITOR,
//            '' => User::ROLE_TRANSLATOR,
        ];
    }

    public function getNewCountryIdAttribute()
    {
        return config('defaults.country.id');
    }

    public function region(): BelongsTo
    {
        return $this->belongsTo(OldRegion::class, 'city_id');
    }

    public function getRegionIdAttribute()
    {
        return ! is_null($this->region) ? optional(Region::whereTranslationLike('name',
            $this->region->name_en)->first())->id : config('defaults.region.id');
    }

    public function getNewCityIdAttribute()
    {
        return config('defaults.city.id');
    }

    public function firebaseUser(): HasMany
    {
        return $this->hasMany(OldFirebaseUsersTokens::class, 'user_id')->whereNotNull('device_type');
    }

    public function getFirebaseDataAttribute(): ?OldFirebaseUsersTokens
    {
        return $this->firebaseUser()->latest()->first();
    }

    public function getUpdatedUsernameAttribute(): string
    {
        if ( ! is_null($this->username)) {
            return $this->username;
        } else {
            $tempString = Str::snake($this->first_name).'_'.$this->tel_number;
            $tempString = str_replace('_', '', $tempString);
            if (empty($tempString)) {
                $tempString = strstr($this->email, '@', 1);
            }

            if (User::whereUsername($tempString)->count()) {
                $tempString = $this->getUuidString(15);
            }

            return $tempString;
        }
    }

    public function getFirstNameAttribute(): string
    {
        return trim(preg_replace('#'.preg_quote($this->last_name, '#').'#', '', $this->name));
    }

    public function getLastNameAttribute(): string
    {
        if ((strpos($this->name, ' ') === false)) {
            return '';
        } else {
            return preg_replace('#.*\s([\w-]*)$#', '$1', $this->name);
        }
    }

    public function getTelCodeNumberAttribute(): ?string
    {
        $countyCode = '';
        $phoneNumber = $this->phone_number;
        $countyCodeAttempts = ['+90', '+964', '+963', '+null'];
        foreach ($countyCodeAttempts as $tempCountryCode) {
            if ((strpos($phoneNumber, $tempCountryCode) !== false)) {
                $countyCode = Str::substr($tempCountryCode, 1);
                break;
            }
        }

        return $countyCode;
    }

    public function getTelNumberAttribute(): ?string
    {
        $countyCode = '';
        $phoneNumber = $this->phone_number;
        $countyCodeAttempts = ['+90', '+964', '+963', '+null'];
        foreach ($countyCodeAttempts as $tempCountryCode) {
            if ((strpos($phoneNumber, $tempCountryCode) !== false)) {
                $countyCode = Str::substr($tempCountryCode, 1);
                break;
            }
        }

        if (empty($countyCode)) {
            return null;
        }
        $phoneNumber = str_replace($countyCode, '', $phoneNumber);
        $phoneNumber = str_replace(['+', ',', ' ', '(', ')'], '', $phoneNumber);
        if (strlen($phoneNumber) < 4) {
            return null;
        }
        if ( ! ctype_digit($phoneNumber)) {
            return null;
        }
        if (count(array_unique(str_split($phoneNumber))) === 1) {
            return null;
        }

        return $phoneNumber;
    }

    public function getRoleNameAttribute()
    {
        $roleNameSnakeCase = self::roleComparing()[$this->type];

        return Str::title(str_replace('-', ' ', $roleNameSnakeCase));
    }

    public function getSettingsAttribute()
    {
        return json_encode(config('defaults.user.settings'));
    }

    public function getMobileAppDetailsAttribute(): array
    {
        return [
            'version' => '0.0.0',
            'buildNumber' => 0,
            'device' => [
                'manufacturer' => '',
                'name' => '',
                'model' => '',
                'platform' => ! is_null($this->firebase_data) ? $this->firebase_data->device_type : '',
                'serial' => ! is_null($this->firebase_data) ? $this->firebase_data->device_id : '',
                'uuid' => '',
                'version' => '',
            ],
        ];
    }
}
