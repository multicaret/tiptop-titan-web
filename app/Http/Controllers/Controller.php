<?php

namespace App\Http\Controllers;

use App\Models\Media;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Model as ModelAlias;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Spatie\MediaLibrary\MediaCollections\FileAdder as FileAdderAlias;
use Spatie\MediaLibrary\MediaCollections\Models\Media as MediaAlias;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public static function distanceBetween($point1latitude, $point1longitude, $point2latitude, $point2longitude)
    {
        return ACOS(SIN($point1latitude * PI() / 180) * sin($point2latitude * PI() / 180) + COS($point1latitude * PI() / 180) * COS($point2latitude * PI() / 180) * COS($point2longitude * PI() / 180 - $point1longitude * PI() / 180)) * 6371;
    }

    /**
     * Handle The Submitted Media.
     *
     * @param        $request
     * @param  string  $inputName
     * @param        $model
     * @param  null  $collectionName
     * @param  string  $editorInputName
     */
    protected function handleSubmittedMedia(
        $request,
        $inputName,
        $model,
        $collectionName = null,
        $editorInputName = 'media-list'
    ) {
        $editorFiles = json_decode($request->$editorInputName);
        $files = $request->file($inputName, []);

        /*if ( ! empty($mediaList) && count($mediaList) != count($files)) {
            $newMediaList = $mediaList;
            $oldMediaList = $mediaList;
            foreach ($newMediaList as $key => $file) {
                if (isset($file->id)) {
                    unset($newMediaList[$key]);
                } else {
                    unset($oldMediaList[$key]);
                }
            }

            $newMediaList = array_values($newMediaList);
            $oldMediaList = array_values($oldMediaList);

            # update old media data
            foreach ($oldMediaList as $file) {
                $media = $model->media()->find($file->id);
                if ($media) {
                    $this->updateMediaData($media, $file);
                }
            }
        }*/

//        $filesTitles = $request->filesTitles;
//        $filesCategories = $request->filesCategories;
        if (count($files)) {
            foreach ($files as $index => $file) {
                $this->updateMediaData($model->addMedia($file)->toMediaCollection($collectionName),
                    isset($editorFiles[$index]) ? $editorFiles[$index] : null, $request->filesTitles[$index] ?? null,
                    $request->filesCategories[$index] ?? null
                );
            }
        }
        # Remove the deleted media
        if ($request->has("$inputName-deleted-media-list")) {
            $deletedMediaList = json_decode($request->input("$inputName-deleted-media-list", []));
            foreach ($deletedMediaList as $mediaId) {
                optional(Media::find($mediaId))->delete();
            }
        }
    }

    /**
     * Update the media date such as (name, order, rotation, crop, ect..)
     *
     * @param      $media
     * @param      $editorMediaObject
     * @param  null  $fileName
     * @param  null  $fileCategory
     *
     * @return mixed
     */
    protected function updateMediaData($media, $editorMediaObject, $fileName = null, $fileCategory = null)
    {
        if ( ! is_null($editorMediaObject) && isset($editorMediaObject->editor)) {
            $manipulations = [];
            if (isset($editorMediaObject->editor->crop)) {
                $manipulations['*']['manualCrop'] =
                    sprintf("%d,%d,%d,%d",
                        $editorMediaObject->editor->crop->width,
                        $editorMediaObject->editor->crop->height,
                        $editorMediaObject->editor->crop->left,
                        $editorMediaObject->editor->crop->top
                    );
            }
            if (isset($editorMediaObject->editor->rotation)) {
                $manipulations['*']['orientation'] = (string) $editorMediaObject->editor->rotation;
            }
            if (count($manipulations)) {
                if ($media instanceof Media) {
                    $media->manipulations = $manipulations;
                } else {
                    $media = $media->withManipulations($manipulations);
                }
            }
        }

        if ( ! empty($fileName)) {
            $media = $media->usingName($fileName);
        }


        $customProperties = [
            'user_id' => auth()->id(),
        ];

        if ( ! is_null($fileCategory)) {
            $customProperties['category_id'] = $customProperties;
        }
        if (isset($editorMediaObject->index)) {
//            $customProperties['order'] = $editorMediaObject->index;
            $media->order_column = $editorMediaObject->index;
        }

        foreach ($customProperties as $index => $customProperty) {
            $media->setCustomProperty($index, $customProperty);
        }
        $media->save();

        return $media;
    }

    protected function updateFileAdderMediaData(FileAdderAlias $adder, $editor, $arg = [], $fileName = null)
    {
        if ( ! is_null($editor) && isset($editor->editor)) {
            $manipulations = [];
            if (isset($editor->editor->crop)) {
                $manipulations['*']['manualCrop'] =
                    sprintf("%d,%d,%d,%d",
                        $editor->editor->crop->width,
                        $editor->editor->crop->height,
                        $editor->editor->crop->left,
                        $editor->editor->crop->top
                    );
            }
            if (isset($editor->editor->rotation)) {
                $manipulations['*']['orientation'] = (string) $editor->editor->rotation;
            }
            if (count($manipulations)) {
                if ($adder instanceof Media) {
                    $adder->manipulations = $manipulations;
                } else {
                    $adder = $adder->withManipulations($manipulations);
                }
            }
        }

        if ( ! empty($fileName)) {
            $adder = $adder->usingName($fileName);
        }

        $customProperties = [
            'user_id' => auth()->id(),
        ];

        $customProperties = array_merge($customProperties, $arg);
        if (isset($editor->index)) {
            $customProperties['order'] = $editor->index;
        }

        if ($adder instanceof MediaAlias) {
            foreach ($customProperties as $index => $customProperty) {
                $adder->setCustomProperty($index, $customProperty);
            }
            $adder->save();
        } else {
            $adder = $adder->withCustomProperties($customProperties);
        }

        return $adder;
    }

    /**
     * Assigned the created media to collection.
     *
     * @param $media
     * @param $model
     * @param $index
     * @param $collectionName
     *
     * @return Media $media
     */
    protected function addMediaToCollection($media, $model, $index, $collectionName)
    {
        return $media->toMediaCollection($collectionName);
    }

    /**
     * @param       $to
     * @param       $emailClass
     * @param  array  $params
     */
    protected function sendEmail($to, $emailClass, $params): void
    {
        $oldLocale = $emailLocale = localization()->getCurrentLocale();
        if (auth()->check() && auth()->user()->language) {
            $emailLocale = auth()->user()->language->code;
        }
        app()->setLocale($emailLocale);
        $emailClassFullPath = "App\\Mail\\$emailClass";
        \Mail::to($to)->send(new $emailClassFullPath(...$params));
        app()->setLocale($oldLocale);
    }

    /**
     * Convert Indian numbers to Arabic ones
     *
     * @param $number
     *
     * @return mixed
     */
    public static function convertNumbersToArabic($number)
    {
        if (is_null($number)) {
            return $number;
        }
        $western_arabic = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        $eastern_arabic = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];

        return str_replace($eastern_arabic, $western_arabic, $number);
    }

    static public function uuid()
    {
        return hash('crc32', time().hash('crc32', time().uniqid()));
    }

    public function trueMessage($route, $routeParameters = [], $message = 'Successfully done! ')
    {
        return redirect()
            ->route($route, $routeParameters)
            ->withInput()
            ->with('message', [
                'type' => 'Success',
                'text' => trans('strings.'.$message)
            ]);
    }

    public function falseMessage($message = 'Sorry, Failed to execute! ')
    {
        return redirect()
            ->back()
            ->withInput()
            ->with('message', [
                'type' => 'Error',
                'text' => trans('strings.'.$message)
            ]);
    }


    static public function numberToReadable($number, $precision = 1, $divisors = null)
    {
        $shorthand = '';
        $divisor = pow(1000, 0);
        if ( ! isset($divisors)) {
            $divisors = [
                $divisor => $shorthand, // 1000^0 == 1
                pow(1000, 1) => 'K', // Thousand
                pow(1000, 2) => 'M', // Million
                pow(1000, 3) => 'B', // Billion
                pow(1000, 4) => 'T', // Trillion
                pow(1000, 5) => 'Qa', // Quadrillion
                pow(1000, 6) => 'Qi', // Quintillion
            ];
        }
        foreach ($divisors as $divisor => $shorthand) {
            if (abs($number) < ($divisor * 1000)) {
                break;
            }
        }

        return number_format($number / $divisor, $precision).$shorthand;
    }

    public static function deductPercentage($number, $percentage)
    {
        return $number - ($number * $percentage / 100);
    }

    public static function percentageInRespectToTwoNumbers($number1, $number2)
    {
        return $number1 / $number2 * 100;
    }

    public static function rgb2hex($rgb)
    {
        return '#'.sprintf('%02x', $rgb['r']).sprintf('%02x', $rgb['g']).sprintf('%02x', $rgb['b']);
    }

    public static function hex2rgb($hex)
    {
        return sscanf($hex, "#%02x%02x%02x");
    }

    /**
     * Determine whether a hex color is light.
     *
     * @param  mixed  $color  Color.
     * @return bool  True if a light color.
     */
    public static function determineHexColorIfLight($color)
    {
        $hex = str_replace('#', '', $color);

        $c_r = hexdec(substr($hex, 0, 2));
        $c_g = hexdec(substr($hex, 2, 2));
        $c_b = hexdec(substr($hex, 4, 2));

        $brightness = (($c_r * 299) + ($c_g * 587) + ($c_b * 114)) / 1000;

        return $brightness > 155;
    }

    public static function adjustUploadedMediaLocation($localUrl)
    {
        return str_replace('../../', "../../../", $localUrl);
    }

    public function handleSubmittedSingleMedia(string $mediaType, Request $request, ModelAlias $model): ?MediaAlias
    {
        $inputKey = "$mediaType-deleted-media-list";
        $editorFiles = json_decode($request->$inputKey);

        if ($request->has($inputKey)) {
            $unattachedMediaId = $request->input($inputKey, []);
            Media::whereId(json_decode($unattachedMediaId))->delete();
        }

        if ($request->hasFile($mediaType)) {
            $originalName = $request->file($mediaType)->getClientOriginalName();
            $mediaFile = $model->addMedia($request->file($mediaType));
            $editor = isset($editorFiles[0]) ? $editorFiles[0] : null;
            $alternativeText = $request->input('$mediaType'.'_alternative_text', $originalName);
            $customProperties = [
                'alternative_text' => $alternativeText
            ];

            return $this->updateFileAdderMediaData($mediaFile, $editor, $customProperties)
                        ->toMediaCollection($mediaType);
        }

        return null;
    }

}
