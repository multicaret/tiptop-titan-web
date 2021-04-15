<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use App\Models\Chain;
use App\Models\Preference;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Response;

class AjaxController extends Controller
{
    public const PAGINATION = 10;
    private $statusCode = 200;

    /**
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @param  int  $statusCode
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
        return Response::json($data, $this->getStatusCode(), $headers);
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


    public function statusChange(Request $request)
    {
        $modelName = $request->relatedModel;
        $objectName = $modelName::find($request->itemId);

        $objectName->status = $request->status;
        $objectName->save();

        return $this->respond([
            'isSuccess' => true,
            'message' => 'Successfully updated',
            'currentStatus' => $request->relatedModel::getAllStatusesRich()[$objectName->status],
        ]);
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

    //todo: refactor this and the status functions to be dynamic
    public function channelChange(Request $request)
    {
        $modelName = $request->relatedModel;
        $objectName = $modelName::find($request->itemId);

        $objectName->channel = $request->channel;
        $objectName->save();

        return $this->respond([
            'isSuccess' => true,
            'message' => 'Successfully updated',
            'currentStatus' => $request->relatedModel::getAllChannelsRich()[$objectName->channel],
        ]);
    }

    public function channelToggle(Request $request)
    {
        $request->validate([
            'table' => 'required',
            'id' => 'required'
        ]);

        if (Schema::hasTable($request->input('table'))) {
            $item = DB::table($request->input('table'))->where('id', $request->input('id'))->first();
            if ($item) {
                $newStatus = $item->channel ? 0 : 1;
                $data = ['channel' => $newStatus];
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
            if ( ! is_null($retrieve)) {
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
        } catch (Exception $e) {
            return $this->respondWithError($e->getMessage());
        }

    }

    public function loadAdminThemeSettings(Request $request): JsonResponse
    {
        $userId = optional(auth()->user())->id;
        $themeSettingsForAuthUser = Preference::where('notes', "userId-$userId")->get();
        if ( ! is_null($themeSettingsForAuthUser)) {
            return $this->respond($themeSettingsForAuthUser->toArray());
        } else {
            return $this->respond([]);
        }
    }

    public function loadChainBranches(Request $request): JsonResponse
    {
        $chainId = $request->input('chain_id');
        try {
            $chain = Chain::find($chainId);
            $getIdTitle = function ($item) {
                return ['id' => $item->id, 'title' => $item->title];
            };
            $allBranches = $chain->branches->map($getIdTitle)->all();
        } catch (Exception $e) {
            return $this->respondWithError($e->getMessage());
        }

        return $this->respond(['branches' => $allBranches]);
    }


    public function syncChain(Chain $chain, Request $request): JsonResponse
    {
        $chainsIds = [$chain->id];
        if ($request->has('extra_ids')) {
            $extraIds = $request->input('extra_ids');
            if (is_string($extraIds)) {
                array_push($chainsIds, explode(',', $extraIds));
            } else {
                array_push($chainsIds, $extraIds);
            }
        }

        Artisan::call('datum:sync-chains', [
            '--id' => $chainsIds
        ]);

        try {
            $outputMessage = (string) Artisan::output();
        } catch (Exception $e) {
            $outputMessage = $e->getMessage();
        }

        $chain->is_synced = true;
        $chain->save();
        return $this->respond([
            'uuid' => $chain->uuid,
            'isSuccess' => true,
            'message' => $outputMessage
        ]);
    }

}
