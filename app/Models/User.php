<?php

namespace App\Models;

use App\Http\Controllers\Controller;
use App\Mail\Welcome;
use App\Notifications\ResetPassword;
use App\Traits\HasGender;
use App\Traits\HasMediaTrait;
use App\Traits\HasStatuses;
use App\Traits\HasViewCount;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo as BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany as HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Sanctum\NewAccessToken;
use Multicaret\Acquaintances\Traits\CanFavorite;
use Multicaret\Acquaintances\Traits\CanRate;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;

/**
 * App\Models\User
 *
 * @property int $id
 * @property string $first
 * @property string|null $last
 * @property string $username
 * @property string|null $email
 * @property string|null $password
 * @property string|null $phone_country_code
 * @property string|null $phone_number
 * @property string|null $bio
 * @property \Illuminate\Support\Carbon|null $dob
 * @property int|null $gender
 * @property string $wallet_reserved_total
 * @property string $wallet_free_total
 * @property int|null $profession_id
 * @property int|null $language_id Native language ID
 * @property int|null $currency_id
 * @property int|null $country_id
 * @property int|null $region_id
 * @property int|null $city_id
 * @property int|null $selected_address_id
 * @property string|null $latitude
 * @property string|null $longitude
 * @property string $avg_rating
 * @property int $rating_count
 * @property int $view_count
 * @property int $total_number_of_orders
 * @property int|null $order_column
 * @property mixed|null $social_networks
 * @property object $settings to handle all sort of settings including notification related such as is_notifiable by email or by push notifications ...etc
 * @property int $status 0:incomplete, 1:draft, 2:published, 3:Inactive, 4..n:CUSTOM
 * @property \Illuminate\Support\Carbon|null $approved_at
 * @property \Illuminate\Support\Carbon|null $phone_verified_at
 * @property \Illuminate\Support\Carbon|null $suspended_at
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property \Illuminate\Support\Carbon|null $last_logged_in_at
 * @property \Illuminate\Support\Carbon|null $last_logged_out_at
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Location[] $addresses
 * @property-read int|null $addresses_count
 * @property-read \App\Models\City|null $city
 * @property-read \App\Models\Country|null $country
 * @property-read \App\Models\Currency|null $currency
 * @property-read mixed $analyst
 * @property-read bool $avatar
 * @property-read bool $cover
 * @property-read mixed $international_phone
 * @property-read mixed $is_admin
 * @property-read bool $is_manager
 * @property-read mixed $is_owner
 * @property-read mixed $is_published
 * @property-read bool $is_super
 * @property-read mixed $is_user
 * @property-read mixed $name
 * @property-read mixed $status_name
 * @property-read mixed $translator
 * @property-read \App\Models\Language|null $language
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection|Media[] $media
 * @property-read int|null $media_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Order[] $orders
 * @property-read int|null $orders_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Permission\Models\Permission[] $permissions
 * @property-read int|null $permissions_count
 * @property-read \App\Models\Region|null $region
 * @property-read \Illuminate\Database\Eloquent\Collection|Role[] $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Sanctum\PersonalAccessToken[] $tokens
 * @property-read int|null $tokens_count
 * @method static \Illuminate\Database\Eloquent\Builder|User active()
 * @method static \Illuminate\Database\Eloquent\Builder|User draft()
 * @method static \Database\Factories\UserFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|User inActive()
 * @method static \Illuminate\Database\Eloquent\Builder|User incomplete()
 * @method static \Illuminate\Database\Eloquent\Builder|User managers()
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User notPublished()
 * @method static \Illuminate\Database\Eloquent\Builder|User notSuper()
 * @method static \Illuminate\Database\Eloquent\Builder|User owners()
 * @method static \Illuminate\Database\Eloquent\Builder|User permission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder|User published()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User role($roles, $guard = null)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereApprovedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAvgRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereBio($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCountryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCurrencyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDob($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereFirst($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLanguageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLast($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLastLoggedInAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLastLoggedOutAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLongitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereMobileApp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereOrderColumn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePhoneCountryCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePhoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePhoneVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereProfessionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRatingCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRegionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereSelectedAddressId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereSettings($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereSocialNetworks($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereSuspendedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTotalNumberOfOrders($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUsername($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereViewCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereWalletFreeTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereWalletReservedTotal($value)
 * @mixin \Eloquent
 */
class User extends Authenticatable implements HasMedia, MustVerifyEmail
{
    use HasApiTokens,
        Notifiable,
        HasViewCount,
        HasMediaTrait,
        CanResetPassword,
        CanRate,
        HasGender,
        HasStatuses,
        HasRoles,
        HasFactory,
        CanFavorite;

    const ROLE_SUPER = 'super';
    const ROLE_ADMIN = 'admin';
    const ROLE_SUPERVISOR = 'supervisor';
    const ROLE_AGENT = 'agent';
    const ROLE_CONTENT_EDITOR = 'content-editor';
    const ROLE_MARKETER = 'marketer';
    const ROLE_BRANCH_OWNER = 'branch-owner';
    const ROLE_BRANCH_MANAGER = 'branch-manager';
    const ROLE_TRANSLATOR = 'translator';
    const ROLE_RESTAURANT_DRIVER = 'restaurant-driver';
    const ROLE_TIPTOP_DRIVER = 'tiptop-driver';
    const ROLE_USER = 'user';

    const STATUS_INCOMPLETE = 0;
    const STATUS_DRAFT = 1;
    const STATUS_PUBLISHED = 2;
    const STATUS_INACTIVE = 3;

    const GENDER_UNSPECIFIED = 0;
    const GENDER_MALE = 1;
    const GENDER_FEMALE = 2;

    protected $fillable = [
        'last_logged_in_at',
        'last_logged_out_at',
        'order_column',
    ];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'dob' => 'date',
        'settings' => 'object',
        'email_verified_at' => 'datetime',
        'approved_at' => 'datetime',
        'phone_verified_at' => 'datetime',
        'suspended_at' => 'datetime',
        'last_logged_in_at' => 'datetime',
        'last_logged_out_at' => 'datetime',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
//        'role',
        'password',
        'remember_token',
    ];


    protected $appends = [
        'name',
        'avatar',
        'cover',
    ];

    protected $with = [
        'defaultAddress',
//        'country',
    ];


    protected static function boot()
    {
        parent::boot();

        static::created(function (User $user) {
            if (app()->environment() == 'production' && ! Str::contains($user->username, 'db-seeder-test-')) {
                Mail::to($user)->send(new Welcome($user));
            }
        });
        static::creating(function (User $user) {
            if (is_null($user->settings)) {
                $user->settings = json_decode(json_encode(config('defaults.user.settings')));
            }
        });
    }

    /**
     * Set the user's first name.
     *
     * @param  string  $value
     * @return void
     */
    public function setFirstAttribute($value)
    {
        $this->attributes['first'] = ucfirst(Controller::convertNumbersToArabic($value));
    }

    /**
     * Set the user's first name.
     *
     * @param  string  $value
     * @return void
     */
    public function setLastAttribute($value)
    {
        $this->attributes['last'] = ucfirst(Controller::convertNumbersToArabic($value));
    }

    /**
     * @return string
     */
    public function getStatusName()
    {
        switch ($this->status) {
            case self::STATUS_INACTIVE:
                return trans('strings.STATUS_INACTIVE');
            case self::STATUS_ACTIVE:
                return trans('strings.STATUS_ACTIVE');
            case self::STATUS_SUSPENDED:
                return trans('strings.STATUS_SUSPENDED');
        }
    }

    /**
     * Scope a query to only exclude Super admins.
     *
     * @param $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNotSuper($query)
    {
        return $query->role(User::ROLE_SUPER);
    }

    /**
     * Scope a query to only get owners.
     *
     * @param $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOwners($query)
    {
        return $query->role(User::ROLE_OWNER);
    }

    public function scopeInActive($query)
    {
        return $query->where('status', self::STATUS_INACTIVE);
    }

    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public static function roleExists($type) //checks if there is such role
    {
        $role = Role::findByName($type);
        if (defined($role)) {
            return true;
        }

        return false;
    }

    public function getNameAttribute()
    {
        return $this->first.' '.$this->last;
    }


    public function getInternationalPhoneAttribute()
    {
        return $this->phone_country_code.$this->phone_number;
    }

    /**
     * Get the avatar attribute.
     *
     * @param  string  $avatar
     *
     * @return bool
     */
    public function getAvatarAttribute()
    {
        $avatar = url(config('defaults.images.user_avatar'));

        if ($this->hasMedia('avatar')) {
            $avatar = $this->getFirstMedia('avatar')->getFullUrl();
        }

        return $avatar;
    }

    /**
     * Get the cover attribute.
     *
     *
     * @return bool
     */
    public function getCoverAttribute()
    {
        $cover = url(config('defaults.images.user_cover'));

        if ($this->hasMedia('cover')) {
            $cover = $this->getFirstMedia('cover')->getFullUrl();
        }

        return $cover;
    }

    public function getIsUserAttribute()
    {
        return $this->hasRole(self::ROLE_USER);
    }

    public function getIsOwnerAttribute()
    {
        return $this->hasRole(self::ROLE_OWNER);
    }

    public function getIsAdminAttribute()
    {
        return $this->hasRole(self::ROLE_ADMIN);
    }

    public function getIsSuperAttribute(): bool
    {
        return $this->hasRole(self::ROLE_SUPER);
    }

    /**
     * @return bool
     */
    public function getIsManagerAttribute(): bool
    {
        return $this->hasAnyRole([self::ROLE_ADMIN, self::ROLE_SUPER]);
    }

    /**
     * Scope a query to only include managers.
     *
     * @param $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeManagers($query)
    {
        return $query->role([self::ROLE_ADMIN, self::ROLE_SUPER]);
    }

    public function getTranslatorAttribute()
    {
        return $this->hasRole(self::ROLE_TRANSLATOR);
    }

    public function getAnalystAttribute()
    {
        return $this->hasRole(self::ROLE_ANALYST);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'user_id');
    }

    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class);
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }


    public function defaultAddress()
    {
        return $this->addresses()->where('is_default', true);
    }

    /**
     * This model has many addresses.
     *
     * @return mixed
     */
    public function addresses()
    {
        return $this->morphMany(Location::class, 'contactable');
    }


    public function couponUsage(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CouponUsage::class, 'redeemer_id');
    }

    /**
     * @param $fullName
     *
     * @return array
     */
    public static function extractFirstAndLastNames($fullName): array
    {
        $first = $fullName;
        $last = null;
        $fullName = explode(' ', $fullName);
        if (count($fullName) > 1) {
            $last = $fullName[count($fullName) - 1];
            unset($fullName[count($fullName) - 1]);
            $first = implode(' ', $fullName);
        }

        return [$first, $last];
    }

    /**
     *
     * @param $phoneCountryCode
     * @param $phoneNumber
     *
     * @return User|\Illuminate\Database\Eloquent\Model|object|null
     */
    public static function getUserByPhone($phoneCountryCode, $phoneNumber)
    {
        return User::where('phone_country_code', $phoneCountryCode)
                   ->where('phone_number', $phoneNumber)->first();
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('avatar')
             ->singleFile()
             ->registerMediaConversions(function (Media $media) {
                 $this->addMediaConversion('medium')
                      ->width(512)
                      ->height(512)
                      ->nonQueued();
                 $this->addMediaConversion('thumbnail')
                      ->crop(Manipulations::CROP_CENTER, 250, 250)
                      ->nonQueued();
             });
        $this->addMediaCollection('cover')
             ->singleFile()
             ->registerMediaConversions(function (Media $media) {
                 $this->addMediaConversion('medium')
                      ->width(1024)
                      ->height(576)
                      ->nonQueued();
                 $this->addMediaConversion('thumbnail')
                      ->width(480)
                      ->height(270)
                      ->nonQueued();
             });
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     *
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }

    public static function getAllRoles()
    {
        return [
            'super' => self::ROLE_SUPER,
            'admin' => self::ROLE_ADMIN,
            'supervisor' => self::ROLE_SUPERVISOR,
            'agent' => self::ROLE_AGENT,
            'content_editor' => self::ROLE_CONTENT_EDITOR,
            'marketer' => self::ROLE_MARKETER,
            'branch_owner' => self::ROLE_BRANCH_OWNER,
            'branch_manager' => self::ROLE_BRANCH_MANAGER,
            'translator' => self::ROLE_TRANSLATOR,
            'restaurant_driver' => self::ROLE_RESTAURANT_DRIVER,
            'tiptop_driver' => self::ROLE_TIPTOP_DRIVER,
            'user' => self::ROLE_USER,
        ];
    }

    public function getAllRolesWithSuper()
    {
        $roles = self::getAllRoles();
        $roles['super'] = self::ROLE_SUPER;

        return $roles;
    }

    public function routeNotificationForOneSignal()
    {
        return ['include_external_user_ids' => [$this->id]];
    }


    /**
     * Get the access tokens that belong to model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function tokens()
    {
        return $this->morphMany(PersonalAccessToken::class, 'tokenable');
    }

    /**
     * Create a new personal access token for the user.
     *
     * @param  string  $name
     * @param $deviceDetails
     * @param  array  $abilities
     * @return \Laravel\Sanctum\NewAccessToken
     */
    public function createToken(string $name, $deviceDetails, array $abilities = ['*']): NewAccessToken
    {
        $token = $this->tokens()->create([
            'name' => $name,
            'token' => hash('sha256', $plainTextToken = Str::random(40)),
            'abilities' => $abilities,
            'mobile_app_details' => json_encode($deviceDetails),
        ]);

        return new NewAccessToken($token, $token->getKey().'|'.$plainTextToken);
    }
}
