<?php

namespace App\Models;

use App\Http\Resources\BranchResource;
use App\Traits\HasMediaTrait;
use App\Traits\HasStatuses;
use App\Traits\HasUuid;
use App\Traits\HasViewCount;
use App\Traits\HasWorkingHours;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;

class Branch extends Model implements HasMedia
{
    use HasMediaTrait,
        HasUuid,
        Translatable,
        HasStatuses,
        HasWorkingHours,
        HasViewCount;

    const STATUS_INCOMPLETE = 0;
    const STATUS_DRAFT = 1;
    const STATUS_PUBLISHED = 2;
    const STATUS_INACTIVE = 3;

    const TYPE_GROCERY = 1;
    const TYPE_FOOD = 2;

    protected $fillable = ['title', 'description'];
    protected $with = ['translations'];
    protected $translatedAttributes = ['title', 'description'];


    public function chain(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Chain::class, 'chain_id');
    }

    public function managers(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class, 'branch_manager', 'manager_id', 'branch_id')
                    ->withPivot('is_primary')
                    ->withTimestamps();
    }

    public function primaryManager()
    {
        return $this->managers()->wherePivot('is_primary', true)->first();
    }

    public function supervisors(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class, 'branch_supervisor', 'supervisor_id', 'branch_id')
                    ->withPivot('is_primary')
                    ->withTimestamps();
    }

    public function primarySupervisor()
    {
        return $this->supervisors()->wherePivot('is_primary', true)->first();
    }

    public function workingHours()
    {
        return $this->morphMany(WorkingHour::class, 'workable');
    }


    public static function getClosestAvailableBranch($latitude, $longitude): array
    {
        $distance = $branch = null;
        $branchesOrderedByDistance = Branch::published()
                                           ->selectRaw('branches.id, DISTANCE_BETWEEN(latitude,longitude,?,?) as distance',
                                               [$latitude, $longitude])
                                           ->orderBy('distance')
                                           ->get();

        foreach ($branchesOrderedByDistance as $branchOrderedByDistance) {
            $branch = Branch::find($branchOrderedByDistance->id);
            $branchWorkingHours = WorkingHour::retrieve($branch);
            if ($branchWorkingHours['isOpen']) {
                $distance = $branchOrderedByDistance->distance;
                $branch = new BranchResource($branch);
                break;
            }
        }

        return [$distance, $branch];
    }
}
