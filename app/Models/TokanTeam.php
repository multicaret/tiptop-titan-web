<?php

namespace App\Models;

use App\Traits\HasStatuses;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TokanTeam extends Model
{
    use HasStatuses;

    public const STATUS_DRAFT = 1;
    public const STATUS_ACTIVE = 2;
    public const STATUS_INACTIVE = 3;

    protected $fillable = [];

    public function user()
    {
        return $this->hasMany(User::class);
    }
}
