<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Preference;
use App\Models\PreferenceTranslation;
use DB;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class PreferenceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  Request  $request
     * @return View
     */
    public function index(Request $request)
    {
        $sections = Preference::sections()->get();

        return view('admin.preferences.index', compact('sections'));
    }


    public function adjustTrackers(Request $request)
    {
        $exceptedKeys = [
            'blog_show',
            'market_branch_product_index',
            'product_show',
            'restaurant_branch_product_index',
        ];

        $callback = function ($item, $key) {
            $item['title'] = \Str::title(str_replace('_', ' ', $key));
            $item['key'] = $key;
            $item['value'] = Preference::retrieveValue($key);
            $item['deep_link'] = Preference::retrieveValue('adjust_deep_link_uri_scheme');

            return [$key => $item];
        };

        $adjustTrackers = collect(config('defaults.adjust_trackers'))
            ->except($exceptedKeys)->mapWithKeys($callback)->values();

        return view('admin.preferences.form', compact('adjustTrackers'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     *
     * @return Response
     * @throws Exception
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        foreach ($request->except(['_token']) as $langKey => $preferences) {
            foreach ($preferences as $key => $value) {

                $preference = Preference::where('key', $key)->first();
                if ($request->hasFile("{$langKey}.{$key}")) {
                    $value = $preference->addMediaFromRequest("{$langKey}.{$key}")
                                        ->toMediaCollection('file')->id;
                }
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

        }

        DB::commit();
        cache()->tags('preferences')->flush();


        return back()->with('message', [
            'type' => 'Success',
            'text' => 'Successfully Updated',
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Preference  $section
     * @return View
     */
    public function edit(Preference $section)
    {
        $sections = Preference::sections()->get();
        $children = Preference::whereGroupName($section->group_name)
                              ->where('type', '!=', 'section')
                              ->get();

        return view('admin.preferences.edit', [
            'currentSection' => $section,
            'sections' => $sections,
            'children' => $children,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  \App\Preference  $preference
     *
     * @return Response
     */
    public function update(Request $request, Preference $preference)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Preference  $preference
     *
     * @return Response
     */
    public function destroy(Preference $preference)
    {
        //
    }
}
