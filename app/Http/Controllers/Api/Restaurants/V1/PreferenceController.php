<?php

namespace App\Http\Controllers\Api\Restaurants\V1;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\PreferenceSectionResource;
use App\Models\Preference;
use App\Models\PreferenceTranslation;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

Class PreferenceController extends BaseApiController
{

    public function index()
    {
        $preferences = Preference::get()
                                 ->groupBy('group_name')
                                 ->map(function (Collection $children, $title) {
                                     $section = $children->first();
                                     $children->shift();
                                     $section->children = $children->sortBy('order_column');

                                     return new PreferenceSectionResource($section);
                                 })
                                 ->toArray();

        return $this->respond(array_values($preferences));
    }

    public function store(Request $request)
    {
        foreach ($request->all() as $langKey => $preferences) {
            foreach ($preferences as $key => $value) {

                if ($request->hasFile("{$langKey}.{$key}")) {
                    $value = $request->file("{$langKey}.{$key}")->store('preferences', 'public');
                }

                $preference = Preference::where('key', $key)->first();
                $preference->translateOrNew($langKey)->value = $value;
                $preference->save();
            }

            $emptyCheckboxesIds = Preference::whereNotIn('key', array_keys($preferences))
                                            ->where('type', 'checkbox')
                                            ->select('id')
                                            ->get()
                                            ->pluck('id')
                                            ->toArray();

            PreferenceTranslation::whereIn('preference_id', $emptyCheckboxesIds)
                                 ->where('locale', $langKey)
                                 ->update([
                                     'value' => null
                                 ]);

            cache()->forget("{$langKey}.preferences");
        }


        return $this->respond([
            'success' => true,
            'message' => 'Successfully Updated!',
        ]);
    }
}
