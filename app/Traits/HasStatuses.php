<?php

namespace App\Traits;

trait HasStatuses
{

    /**
     * Scope a query to only include published records.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePublished($query)
    {
        return $query->where('status', self::STATUS_PUBLISHED);
    }

    /**
     * Scope a query to only include NOT published records.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNotPublished($query)
    {
        return $query->where('status', '!=', self::STATUS_PUBLISHED);
    }

    /**
     * Scope a query to only include incomplete records.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeIncomplete($query)
    {
        return $query->where('status', self::STATUS_INCOMPLETE);
    }

    /**
     * Scope a query to only include draft records.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDraft($query)
    {
        return $query->where('status', self::STATUS_DRAFT);
    }

    /**
     * Scope a query to only include inactive records.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeInactive($query)
    {
        return $query->where('status', self::STATUS_INACTIVE);
    }

    public function getIsPublishedAttribute()
    {
        return $this->status === self::STATUS_PUBLISHED;
    }

    public function getStatusNameAttribute()
    {
        switch ($this->status) {
            case self::STATUS_INCOMPLETE:
                return trans('strings.incomplete');
            case self::STATUS_DRAFT:
                return trans('strings.draft');
            case self::STATUS_PUBLISHED:
                return trans('strings.published');
            case self::STATUS_INACTIVE:
                return trans('strings.inactive');
            default:
                return null;
        }
    }

    public static function getStatusesArray(): array
    {
        return [
            self::STATUS_INCOMPLETE => 'Incomplete',
            self::STATUS_DRAFT => 'Draft',
            self::STATUS_PUBLISHED => 'Published',
            self::STATUS_INACTIVE => 'Inactive',
        ];
    }

    public static function getAllStatusesRich(): array
    {
        return [
            self::STATUS_PUBLISHED => [
                'id' => self::STATUS_PUBLISHED,
                'title' => trans("strings.published"),
                'class' => 'primary',
            ],
            self::STATUS_DRAFT => [
                'id' => self::STATUS_DRAFT,
                'title' => trans("strings.draft"),
                'class' => 'light',
            ],
            self::STATUS_INCOMPLETE => [
                'id' => self::STATUS_INCOMPLETE,
                'title' => trans("strings.incomplete"),
                'class' => 'danger',
            ],
            self::STATUS_INACTIVE => [
                'id' => self::STATUS_INACTIVE,
                'title' => trans("strings.inactive"),
                'class' => 'dark',
            ],
        ];
    }
}
