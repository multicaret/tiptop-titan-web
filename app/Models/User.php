<?php

namespace App\Models;

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
use Multicaret\Acquaintances\Traits\CanFavorite;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements HasMedia, MustVerifyEmail
{
    use HasApiTokens,
        Notifiable,
        HasViewCount,
        HasMediaTrait,
        CanResetPassword,
        HasGender,
        HasStatuses,
        HasRoles,
        HasFactory,
        CanFavorite;

    const ROLE_SUPER = 'Super';
    const ROLE_ADMIN = 'Admin';
    const ROLE_SUPERVISOR = 'Supervisor';
    const ROLE_AGENT = 'Agent';
    const ROLE_CONTENT_EDITOR = 'Content Editor';
    const ROLE_MARKETER = 'Marketer';
    const ROLE_BRANCH_OWNER = 'Branch Owner';
    const ROLE_BRANCH_MANAGER = 'Branch Manager';
    const ROLE_EDITOR = 'Editor';
    const ROLE_TRANSLATOR = 'Translator';
    const ROLE_RESTAURANT_DRIVER = 'Restaurant Driver';
    const ROLE_TIPTOP_DRIVER = 'Tiptop Driver';
    const ROLE_USER = 'User';

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
        'mobile_app' => 'object',
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
            if (is_null($user->mobile_app)) {
                $user->mobile_app = json_decode(json_encode(config('defaults.user.mobile_app')));
            }
            if (is_null($user->settings)) {
                $user->settings = json_decode(json_encode(config('defaults.user.settings')));
            }
        });
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
            'editor' => self::ROLE_EDITOR,
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
}
