<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\BaseApiController;
use App\Models\Branch;
use App\Models\Cart;
use App\Models\Coupon;
use App\Models\Currency;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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
     * @param  Request  $request
     * @return JsonResponse
     * @throws BindingResolutionException
     */
    public function validateCoupon($code, Request $request): JsonResponse
    {
        $validationRules = [
            'branch_id' => 'required',
            // cart_id is required for security reasons, please don't remove it
            'cart_id' => 'required',
            'selected_address_id' => 'required',
        ];
        $activeCart = Cart::with('branch')
                          ->whereId($request->input('cart_id'))
                          ->first();
        $branch = $activeCart->branch;

        if ($branch->type == Branch::CHANNEL_FOOD_OBJECT) {
            $validationRules['delivery_type'] = 'required';
        }

        $validator = validator()->make($request->all(), $validationRules);
        if ($validator->fails()) {
            return $this->respondValidationFails($validator->errors());
        }

        $coupon = Coupon::active()->where('redeem_code', $code)->first();
        if (is_null($coupon)) {
            return $this->respondWithMessage("There is no such coupon code($code)");
        }

        [
            $isExpirationDateAndUsageValid, $validationExpirationAndUsageMessage
        ] = $coupon->validateExpirationDateAndUsageCount($branch->type);

        if ( ! $isExpirationDateAndUsageValid) {
            return $this->setStatusCode(Response::HTTP_BAD_REQUEST)->respond([
                'errors' => $validationExpirationAndUsageMessage
            ]);
        }

        [
            $isAmountValid,
            $totalDiscountedAmount,
            $couponValidationMessage
        ] = $coupon->validateCouponDiscountAmount($activeCart->total);

        if ( ! $isAmountValid) {
            return $this->setStatusCode(Response::HTTP_BAD_REQUEST)->respond(null, [
                'coupon' => [$couponValidationMessage]
            ]);
        }
        $isDeliveryTypeTipTop = $request->input('delivery_type') == 'tiptop';
        $deliveryFee = $branch->calculateDeliveryFee($activeCart->total, $isDeliveryTypeTipTop,
            $coupon->has_free_delivery, $request->input('selected_address_id'));

        $data = [];
        foreach (
            [
                'discountedAmount' => $totalDiscountedAmount,
                'deliveryFee' => $deliveryFee,
                'totalBefore' => $activeCart->total,
                'totalAfter' => $activeCart->total - $totalDiscountedAmount,
                'grandTotal' => ($activeCart->total - $totalDiscountedAmount) + $deliveryFee,
            ] as $index => $item
        ) {
            $data[$index] = [
                'raw' => (double) $item,
                'formatted' => Currency::format($item),
            ];
        }

        return $this->respond($data);
    }

}
