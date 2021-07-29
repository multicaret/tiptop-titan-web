<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait HasStatuses
{

    public function getIsActiveAttribute(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function getIsInactiveAttribute(): bool
    {
        return $this->status === self::STATUS_INACTIVE;
    }

    /**
     * Scope a query to only include active records.
     *
     * @param  Builder  $query
     *
     * @return Builder
     */
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    /**
     * Scope a query to only include everything BUT active records.
     *
     * @param  Builder  $query
     *
     * @return Builder
     */
    public function scopeNotActive($query)
    {
        return $query->where('status', '!=', self::STATUS_ACTIVE);
    }

    /**
     * Scope a query to only include draft records.
     *
     * @param  Builder  $query
     *
     * @return Builder
     */
    public function scopeDraft($query)
    {
        return $query->where('status', self::STATUS_DRAFT);
    }

    /**
     * Scope a query to only include draft records.
     *
     * @param  Builder  $query
     *
     * @return Builder
     */
    public function scopeNotDraft($query)
    {
        return $query->where('status', '!=', self::STATUS_DRAFT);
    }

    /**
     * Scope a query to only include inactive records.
     *
     * @param  Builder  $query
     *
     * @return Builder
     */
    public function scopeInactive($query)
    {
        return $query->where('status', self::STATUS_INACTIVE);
    }

    public function getStatusNameAttribute()
    {
        switch ($this->status) {
            case self::STATUS_DRAFT:
                return trans('strings.draft');
            case self::STATUS_ACTIVE:
                return trans('strings.active');
            case self::STATUS_INACTIVE:
                return trans('strings.inactive');
            default:
                return null;
        }
    }

    public function getStatusClassAttribute()
    {
        switch ($this->status) {
            case self::STATUS_DRAFT:
                return 'warning';
            case self::STATUS_ACTIVE:
                return 'secondary';
            case self::STATUS_INACTIVE:
                return 'danger';
            default:
                return null;
        }
    }

    public static function getStatusesArray(): array
    {
        return [
            self::STATUS_DRAFT => 'Draft',
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_INACTIVE => 'Inactive',
        ];
    }

    public static function getAllStatusesRich(): array
    {
        return [
            self::STATUS_ACTIVE => [
                'id' => self::STATUS_ACTIVE,
                'title' => trans('strings.active'),
                'class' => 'primary',
            ],
            self::STATUS_DRAFT => [
                'id' => self::STATUS_DRAFT,
                'title' => trans('strings.draft'),
                'class' => 'light',
            ],
            self::STATUS_INACTIVE => [
                'id' => self::STATUS_INACTIVE,
                'title' => trans('strings.inactive'),
                'class' => 'dark',
            ],
        ];
    }
}
