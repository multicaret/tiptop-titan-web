<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\BaseApiController;
use Illuminate\Http\Request;
use Log;

class LogController extends BaseApiController
{
    /**
     * @param  Request  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $rules = [
            'data' => 'required',
            'type' => 'in:error,info,warning,alert,debug,notice',
        ];

        $validator = validator()->make($request->all(), $rules);
        if ($validator->fails()) {
            return $this->respondValidationFails($validator->errors());
        }

        ! empty($type = $request->type)
            ? Log::$type($request->data)
            : Log::error($request->data);

        return $this->respondWithMessage(__('Your submitted data has been logged!'));
    }
}
