<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Preference;
use App\Models\PreferenceTranslation;
use Illuminate\Http\Request;
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


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function store(Request $request)
    {
        \DB::beginTransaction();
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

        \DB::commit();
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
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Preference  $preference
     *
     * @return \Illuminate\Http\Response
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
     * @return \Illuminate\Http\Response
     */
    public function destroy(Preference $preference)
    {
        //
    }
}
