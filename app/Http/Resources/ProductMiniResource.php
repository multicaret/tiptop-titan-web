<?php

namespace App\Http\Resources;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

/** @mixin Product */
class ProductMiniResource extends JsonResource
{
    /**
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $signedInUserIsBranchManager = false;
        $user = auth('sanctum')->user();
        if ( ! is_null($user) && ($user->is_branch_manager)) {
            $signedInUserIsBranchManager = true;
        }


        return [
            'id' => $this->id,
            'englishTitle' => optional($this->translate('en'))->title,
            'title' => $this->title,
            'excerpt' => [
                'raw' => Str::limit(strip_tags($this->description), 500),
                'formatted' => Str::limit(strip_tags($this->description), 500),
            ],
            'customBannerText' => $this->custom_banner_text,
            'unitText' => $this->unit_text,
            'unit' => new UnitResource($this->unit),
            'availableQuantity' => $this->available_quantity,
            'minimumOrderableQuantity' => $this->minimum_orderable_quantity,
            'price' => [
                'raw' => (double) $this->price,
                'formatted' => $this->price_formatted,
            ],
            'discountedPrice' => $this->price_discount_amount === 0 ? null : [
                'raw' => (double) $this->discounted_price,
                'formatted' => $this->discounted_price_formatted,
            ],
            'media' => [
//                'cover' => $this->cover,
                'coverSmall' => $this->cover_small,
//                'coverThumbnail' => $this->cover_thumbnail,
//                'coverFull' => $this->cover_full,
//                'gallery' => $this->gallery,
            ],
            'isActive' => $this->when($signedInUserIsBranchManager, $this->status == Product::STATUS_ACTIVE),
            'isDisabled' => $this->when(! $signedInUserIsBranchManager, $this->status == Product::STATUS_INACTIVE),
        ];
    }
}
