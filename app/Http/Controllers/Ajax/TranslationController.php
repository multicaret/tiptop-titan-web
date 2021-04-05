<?php

namespace App\Http\Controllers\Ajax;


use App\Models\Translation;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;


class TranslationController extends AjaxController
{
    public function translationUpdate(Request $request)
    {
        $id = $request->input('id');
        $value = $request->input('value');
        $localeKey = $request->input('localeKey');
        try {
            $translation = Translation::find($id);
            if (!is_null($translation)) {
                $translation->translateOrNew($localeKey)->value = $value;
                $translation->save();
            }
        } catch (Exception $e) {
            return $this->respond([
                'isSuccess' => false,
                'message' => $e->getMessage(),
            ]);
        }
        cache()->tags('translations')->flush();
        return $this->respond([
            'isSuccess' => true,
            'message' => 'Successfully updated',
        ]);
    }


    public function updateTranslationsData()
    {
        $data = [];
        try {
            Artisan::call('translation:import --update-only=true');
            if (auth()->user()->is_manager) {
                $data['html'] = '<div style="overflow-y:auto;max-height: 300px">'.nl2br(Artisan::output()). '</div>';
            }
        } catch (Exception $e) {
            return $this->respond([
                'isSuccess' => false,
                'message' => $e->getMessage(),
            ]);
        }
        cache()->tags('translations')->flush();
        return $this->respond([
            'isSuccess' => true,
            'message' => 'Successfully updated',
            'data' => $data,
        ]);
    }
}
