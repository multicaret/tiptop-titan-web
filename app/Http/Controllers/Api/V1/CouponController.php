<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\CouponResource;
use App\Models\Coupon;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class CouponController extends BaseApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        //
    }

    /**
     * Validate a coupon
     *
     * @param $code
     * @return JsonResponse
     */
    public function validateCoupon($code): JsonResponse
    {
        $coupon = Coupon::where('redeem_code', $code)->first();

        $couponValidation = Coupon::retrieveValidation($coupon);

        if ($couponValidation['type'] == 'error') {
            return $this->setStatusCode(Response::HTTP_BAD_REQUEST)->respond([
                'errors' => $couponValidation['message']
            ]);
        } elseif ($couponValidation['type'] == 'undefined') {
            return $this->respondNotFound([
                $couponValidation['message'],
            ]);
        }

        return $this->respond([
            $couponValidation['data'],
        ]);
    }

}
