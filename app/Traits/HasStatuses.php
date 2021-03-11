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
            self::STATUS_INCOMPLETE => 'incomplete',
            self::STATUS_DRAFT => 'draft',
            self::STATUS_PUBLISHED => 'published',
            self::STATUS_INACTIVE => 'inactive',
        ];
    }
}
