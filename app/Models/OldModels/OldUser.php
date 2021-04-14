<?php

namespace App\Models\OldModels;


use App\Models\Branch;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
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
 * @property-read string $first_name
 * @property-read string $last_name
 * @method static \Illuminate\Database\Eloquent\Builder|OldUser newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OldUser newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OldUser query()
 * @method static \Illuminate\Database\Eloquent\Builder|OldUser whereAgentDevice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OldUser whereAgentOs($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OldUser whereBirthDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OldUser whereBranchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OldUser whereCityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OldUser whereConfirmationCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OldUser whereCountryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OldUser whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OldUser whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OldUser whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OldUser whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OldUser whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OldUser whereLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OldUser whereLocale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OldUser whereLongitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OldUser whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OldUser whereNotificationMode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OldUser whereOnline($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OldUser whereOrdersCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OldUser wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OldUser wherePhoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OldUser whereReceiveFcmNotification($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OldUser whereRegistrationType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OldUser whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OldUser whereSendCodeAgainDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OldUser whereSendCodeAttempts($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OldUser whereSex($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OldUser whereSocialId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OldUser whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OldUser whereSubType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OldUser whereSubmitCodeAgainDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OldUser whereSubmitCodeAttempts($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OldUser whereTokens($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OldUser whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OldUser whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OldUser whereUsername($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OldUser whereVerificationCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OldUser whereWorkiomId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OldUser whereZohoId($value)
 * @mixin \Eloquent
 * @property-read string|null $tel_number
 * @property-read string|null $tel_code_number
 */
class OldUser extends OldModel
{
    protected $table = 'users';
    protected $primaryKey = 'id';


    public const STATUS_ACTIVE = 'ACTIVE';
    public const STATUS_DISABLED = 'DISABLED';
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
            'orders_count' => 'total_number_of_orders',
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

    public function getUpdatedUsernameAttribute(): string
    {
        if ( ! is_null($this->username)) {
            return $this->username;
        } else {
            $tempString = \Str::snake($this->first_name).'_'.$this->tel_number;
            $tempString = str_replace('_', '', $tempString);
            if (empty($tempString)) {
                $tempString = strstr($this->email, '@', 1);
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
                $countyCode = \Str::substr($tempCountryCode, 1);
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
                $countyCode = \Str::substr($tempCountryCode, 1);
                break;
            }
        }

        if (empty($countyCode)) {
            return null;
        }
        $phoneNumber = str_replace($countyCode, '', $phoneNumber);
        $phoneNumber = str_replace(['+', ' ', '(', ')'], '', $phoneNumber);
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
}
