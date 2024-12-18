<?php

namespace App\Traits;


use App\Models\Activity;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use ReflectionClass;
use ReflectionException;

trait RecordsActivity
{
    /**
     * Boot the trait.
     */
    protected static function bootRecordsActivity()
    {
        if (auth()->guest()) {
            return;
        }

        foreach (static::getActivitiesToRecord() as $event) {
            static::$event(function ($model) use ($event) {
                $model->recordActivity($event);
            });
        }

        static::deleting(function ($model) {
            $model->activity()->delete();
        });
    }

    /**
     * Fetch all model events that require activity recording.
     *
     * @return array
     */
    protected static function getActivitiesToRecord()
    {
        return ['created'];
    }

    /**
     * Fetch the activity relationship.
     *
     * @return MorphMany
     */
    public function activity()
    {
        return $this->morphMany(Activity::class, 'subject');
    }

    /**
     * Record new activity for the model.
     *
     * @param  string  $event
     *
     * @throws ReflectionException
     */
    public function recordActivity(string $event, $differences = null)
    {
        /*$this->activity()->where([
            'user_id' => auth()->id(),
            'type' => $this->getActivityType($event)
        ])->delete();*/
        $this->activity()->firstOrCreate([
            'user_id' => auth()->id() ?? 1,
            'type' => $this->getActivityType($event),
            'differences' => $differences ?? $this->getDirty()
        ]);
    }

    /**
     * @param      $type
     * @param  bool  $singleton
     * @param  bool  $isPrivate
     */
    public function recordActivityRaw($type, $singleton = false, $isPrivate = false)
    {
        if ($singleton) {
            $this->activity()->where([
                'user_id' => auth()->id(),
                'type' => $type
            ])->delete();
        }
        $data = [
            'user_id' => auth()->id(),
            'type' => $type
        ];
        if ($isPrivate) {
            $data['is_private'] = true;
        }
        dd($this, $this->activity);
        $this->activity()->firstOrCreate($data);
    }

    /**
     * Determine the activity type.
     *
     * @param  string  $event
     *
     * @return string
     * @throws ReflectionException
     */
    protected function getActivityType($event)
    {
        $type = strtolower((new ReflectionClass($this))->getShortName());

        return "{$event}_{$type}";
    }

    public static function getActivityLogDifference($columnName, $value)
    {
        $activityLogDifferenceItem = self::getVisibleColumnsInActivityLogDifference($columnName);
        if ( ! is_null($activityLogDifferenceItem)) {
            $activityLogDifferenceItem['value'] = self::getFormattedActivityLogDifferenceItem($activityLogDifferenceItem,
                $columnName, $value);

            return $activityLogDifferenceItem;
        }

        return false;
    }
}
