<?php

namespace App\Models;

use App\Http\Controllers\Controller;
use App\Mail\Welcome;
use App\Notifications\ResetPassword;
use App\Traits\HasAppTypes;
use App\Traits\HasGender;
use App\Traits\HasMediaTrait;
use App\Traits\HasStatuses;
use App\Traits\HasViewCount;
use Eloquent;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo as BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany as HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\DatabaseNotificationCollection;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Sanctum\NewAccessToken;
use Multicaret\Acquaintances\Traits\CanFavorite;
use Multicaret\Acquaintances\Traits\CanRate;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Permission\Models\Permission;
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
 * @property Carbon|null $dob
 * @property int|null $gender
 * @property string $wallet_reserved_total
 * @property string $wallet_free_total
 * @property int|null $profession_id
 * @property int|null $language_id Native language ID
 * @property int|null $currency_id
 * @property int|null $country_id
 * @property int|null $region_id
 * @property int|null $city_id
 * @property int|null $branch_id
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
 * @property int $status 1:draft, 2:active, 3:Inactive, 4..n:CUSTOM
 * @property Carbon|null $approved_at
 * @property Carbon|null $phone_verified_at
 * @property Carbon|null $suspended_at
 * @property Carbon|null $email_verified_at
 * @property Carbon|null $last_logged_in_at
 * @property Carbon|null $last_logged_out_at
 * @property int $employment 1:employee, 2:freelancer
 * @property string|null $shift
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|\App\Models\Location[] $addresses
 * @property-read int|null $addresses_count
 * @property-read \App\Models\Branch|null $branch
 * @property-read Collection|\App\Models\Cart[] $carts
 * @property-read int|null $carts_count
 * @property-read \App\Models\City|null $city
 * @property-read \App\Models\Country|null $country
 * @property-read Collection|\App\Models\CouponUsage[] $couponUsages
 * @property-read int|null $coupon_usages_count
 * @property-read \App\Models\Currency|null $currency
 * @property-read mixed $analyst
 * @property-read bool $avatar
 * @property-read bool $cover
 * @property-read mixed $international_phone
 * @property-read bool $is_active
 * @property-read mixed $is_admin
 * @property-read bool $is_inactive
 * @property-read bool $is_manager
 * @property-read mixed $is_owner
 * @property-read bool $is_super
 * @property-read mixed $is_user
 * @property-read mixed $name
 * @property-read mixed $role
 * @property-read mixed $status_name
 * @property-read mixed $translator
 * @property-read \App\Models\Language|null $language
 * @property-read MediaCollection|Media[] $media
 * @property-read int|null $media_count
 * @property-read DatabaseNotificationCollection|DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read Collection|\App\Models\Order[] $orders
 * @property-read int|null $orders_count
 * @property-read Collection|Permission[] $permissions
 * @property-read int|null $permissions_count
 * @property-read \App\Models\Region|null $region
 * @property-read Collection|Role[] $roles
 * @property-read int|null $roles_count
 * @property-read Collection|\App\Models\PersonalAccessToken[] $tokens
 * @property-read int|null $tokens_count
 * @method static Builder|User active()
 * @method static Builder|User draft()
 * @method static \Database\Factories\UserFactory factory(...$parameters)
 * @method static Builder|User foods()
 * @method static Builder|User groceries()
 * @method static Builder|User inActive()
 * @method static Builder|User managers()
 * @method static Builder|User newModelQuery()
 * @method static Builder|User newQuery()
 * @method static Builder|User notActive()
 * @method static Builder|User notSuper()
 * @method static Builder|User owners()
 * @method static Builder|User permission($permissions)
 * @method static Builder|User query()
 * @method static Builder|User role($roles, $guard = null)
 * @method static Builder|User whereApprovedAt($value)
 * @method static Builder|User whereAvgRating($value)
 * @method static Builder|User whereBio($value)
 * @method static Builder|User whereBranchId($value)
 * @method static Builder|User whereCityId($value)
 * @method static Builder|User whereCountryId($value)
 * @method static Builder|User whereCreatedAt($value)
 * @method static Builder|User whereCurrencyId($value)
 * @method static Builder|User whereDob($value)
 * @method static Builder|User whereEmail($value)
 * @method static Builder|User whereEmailVerifiedAt($value)
 * @method static Builder|User whereEmployment($value)
 * @method static Builder|User whereFirst($value)
 * @method static Builder|User whereGender($value)
 * @method static Builder|User whereId($value)
 * @method static Builder|User whereLanguageId($value)
 * @method static Builder|User whereLast($value)
 * @method static Builder|User whereLastLoggedInAt($value)
 * @method static Builder|User whereLastLoggedOutAt($value)
 * @method static Builder|User whereLatitude($value)
 * @method static Builder|User whereLongitude($value)
 * @method static Builder|User whereOrderColumn($value)
 * @method static Builder|User wherePassword($value)
 * @method static Builder|User wherePhoneCountryCode($value)
 * @method static Builder|User wherePhoneNumber($value)
 * @method static Builder|User wherePhoneVerifiedAt($value)
 * @method static Builder|User whereProfessionId($value)
 * @method static Builder|User whereRatingCount($value)
 * @method static Builder|User whereRegionId($value)
 * @method static Builder|User whereRememberToken($value)
 * @method static Builder|User whereSelectedAddressId($value)
 * @method static Builder|User whereSettings($value)
 * @method static Builder|User whereShift($value)
 * @method static Builder|User whereSocialNetworks($value)
 * @method static Builder|User whereStatus($value)
 * @method static Builder|User whereSuspendedAt($value)
 * @method static Builder|User whereTotalNumberOfOrders($value)
 * @method static Builder|User whereUpdatedAt($value)
 * @method static Builder|User whereUsername($value)
 * @method static Builder|User whereViewCount($value)
 * @method static Builder|User whereWalletFreeTotal($value)
 * @method static Builder|User whereWalletReservedTotal($value)
 * @mixin Eloquent
 * @property int|null $team_id
 * @property-read \App\Models\TokanTeam|null $team
 * @method static Builder|User whereTeamId($value)
 * @property int|null $tookan_id
 * @property-read bool $is_food
 * @property-read bool $is_grocery
 * @method static Builder|User whereTookanId($value)
 */
class User extends Authenticatable implements HasMedia, MustVerifyEmail
{
    use CanFavorite;
    use CanRate;
    use CanResetPassword;
    use HasApiTokens;
    use HasFactory;
    use HasGender;
    use HasMediaTrait;
    use HasRoles;
    use HasStatuses;
    use HasAppTypes;
    use HasViewCount;
    use Notifiable;

    public const ROLE_SUPER = 'super';
    public const ROLE_ADMIN = 'admin';
    public const ROLE_SUPERVISOR = 'supervisor';
    public const ROLE_AGENT = 'agent';
    public const ROLE_EDITOR = 'editor';
    public const ROLE_CONTENT_EDITOR = 'content-editor';
    public const ROLE_MARKETER = 'marketer';
    public const ROLE_BRANCH_OWNER = 'branch-owner';
    public const ROLE_BRANCH_MANAGER = 'branch-manager';
    public const ROLE_TRANSLATOR = 'translator';
    public const ROLE_RESTAURANT_DRIVER = 'restaurant-driver';
    public const ROLE_TIPTOP_DRIVER = 'tiptop-driver';
    public const ROLE_USER = 'user';
    public const ROLE_USER_SIDE = 'user-side';

    public const EMPLOYMENT_EMPLOYEE = 1;
    public const EMPLOYMENT_FREELANCER = 2;

    public const STATUS_DRAFT = 1;
    public const STATUS_ACTIVE = 2;
    public const STATUS_INACTIVE = 3;

    public const GENDER_UNSPECIFIED = 0;
    public const GENDER_MALE = 1;
    public const GENDER_FEMALE = 2;

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

    public static function rolesHaving($index): array
    {
        $roleVariations = [
            'branches' => [
                User::ROLE_RESTAURANT_DRIVER,
                User::ROLE_BRANCH_OWNER,
                User::ROLE_BRANCH_MANAGER,
            ],
            'employment' => [
                User::ROLE_TIPTOP_DRIVER,
            ],
        ];

        return $roleVariations[$index];
    }

    public static function getEmploymentsArray()
    {
        return [
            User::EMPLOYMENT_EMPLOYEE => trans('strings.employee'),
            User::EMPLOYMENT_FREELANCER => trans('strings.freelancer'),
        ];
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
     * @return Builder
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
     * @return Builder
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
        return '+'.$this->phone_country_code.$this->phone_number;
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

    public function getRoleAttribute()
    {
        return $this->roles()->first();
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
     * @return Builder
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

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function branches($role = null)
    {
        switch (is_null($role) ? Str::kebab($this->role->name) : $role) {
            case self::ROLE_RESTAURANT_DRIVER:
                $table = 'branch_driver';
                break;
            case self::ROLE_BRANCH_OWNER:
                $table = 'branch_owner';
                break;
            case self::ROLE_BRANCH_MANAGER:
                $table = 'branch_manager';
                break;
        }
        $foreignPivotKey = 'user_id';
        $relatedPivotKey = 'branch_id';

        return $this->belongsToMany(User::class, $table, $foreignPivotKey, $relatedPivotKey)
//                    ->withPivot([$withPivot])
                    ->withTimestamps();
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(TokanTeam::class);
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


    public function couponUsages(): HasMany
    {
        return $this->hasMany(CouponUsage::class, 'redeemer_id');
    }

    public function carts(): HasMany
    {
        return $this->hasMany(Cart::class, 'user_id');
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
     * @return User|Model|object|null
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
            'user_side' => self::ROLE_USER_SIDE,
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
     * @return MorphMany
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
     * @return NewAccessToken
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

    /**
     * @param $branchId
     * @return Cart|Model|object|null
     */
    public function activeCart($branchId): Cart
    {
        return Cart::getCurrentlyActiveCart($this->id, $branchId);
    }

}
