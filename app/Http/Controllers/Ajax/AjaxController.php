<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use App\Models\Preference;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AjaxController extends Controller
{
    const PAGINATION = 10;
    private $statusCode = 200;

    /**
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @param int $statusCode
     *
     * @return $this
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    public function respondNotFound($message = 'Not Found!')
    {
        return $this->setStatusCode(404)->respondWithError($message);
    }

    public function respondWithError($message)
    {
        return $this->respond([
            'error' => [
                'message' => $message,
                'status_code' => $this->getStatusCode()
            ]
        ]);
    }

    public function respond($data, $headers = [])
    {
        return \Response::json($data, $this->getStatusCode(), $headers);
    }

    public function respondWithPagination(LengthAwarePaginator $items, $data)
    {
        return $this->respond(
            array_merge($data, [
                'paginator' => [
                    'total_items' => $items->total(),
                    'total_pages' => $items->lastPage(),
                    'current_page' => $items->currentPage(),
                    'items_per_page' => $items->perPage()
                ]
            ])
        );
    }

    public function statusToggle(Request $request)
    {
        $request->validate([
            'table' => 'required',
            'id' => 'required'
        ]);

        if (Schema::hasTable($request->input('table'))) {
            $item = DB::table($request->input('table'))->where('id', $request->input('id'))->first();
            if ($item) {
                $newStatus = $item->status ? 0 : 1;
                $data = ['status' => $newStatus];
                if (Schema::hasColumn($request->input('table'), 'suspended_at')) {
                    $data['suspended_at'] = ($newStatus == 0) ? now() : null;
                }
                DB::table($request->input('table'))->where('id', $request->input('id'))->update($data);
            }
        }
    }

    public function saveAdminThemeSettings(Request $request): JsonResponse
    {
        $userId = optional(auth()->user())->id;
        $key = $request->input('key');
        $value = $request->input('value');
        $retrieve = Preference::retrieve("$key-$userId");
        try {
            if (!is_null($retrieve)) {
                $retrieve->value = $value;
                $retrieve->notes = "userId-$userId";
                $retrieve->save();
            } else {
                Preference::create([
                    'key' => "$key-$userId",
                    'notes' => "userId-$userId",
                    'value' => $value
                ]);
            }
            return $this->respond([
                'isSuccess' => true
            ]);
        } catch (\Exception $e) {
            return $this->respondWithError($e->getMessage());
        }

    }

    public function loadAdminThemeSettings(Request $request): JsonResponse
    {
        $userId = optional(auth()->user())->id;
        $themeSettingsForAuthUser = Preference::where('notes', "userId-$userId")->get();
        if (!is_null($themeSettingsForAuthUser)) {
            return $this->respond($themeSettingsForAuthUser->toArray());
        } else {
            return $this->respond([]);
        }
    }
}
