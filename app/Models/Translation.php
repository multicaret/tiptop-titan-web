<?php

namespace App\Models;

use App\Traits\HasStatuses;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Translation extends Model
{
    use HasFactory,
        Translatable,
        HasStatuses;

    const STATUS_INCOMPLETE = 0;
    const STATUS_DRAFT = 1;
    const STATUS_PUBLISHED = 2;
    const STATUS_INACTIVE = 3;

    protected $fillable = ['key','group', 'value'];
    protected $with = ['translations'];
    protected $translatedAttributes = ['value'];

    public function scopeGroup($query, $groupName) {
        return $query->where('group' , $groupName);
    }

    public static function getTranslationGroupsFromFiles(): array
    {
        $allGroupsFiles = [];
        $defaultLocale = localization()->getDefaultLocale();
        $directoryIsExists = \File::exists(app()['path.lang']."/{$defaultLocale}/");
        if ($directoryIsExists) {
            $allFiles = \File::files(app()['path.lang']."/{$defaultLocale}/");
            $allGroupsFiles = collect($allFiles)->map(function ($file) {
                return pathinfo($file, PATHINFO_FILENAME);
            })->toArray();
        }
        return $allGroupsFiles;
    }
}
