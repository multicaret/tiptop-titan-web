<?php

namespace App\Http\Controllers\Api\Restaurants\V1;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\CityResource;
use App\Http\Resources\JetOrderResource;
use App\Http\Resources\RegionResource;
use App\Models\Branch;
use App\Models\City;
use App\Models\Currency;
use App\Models\JetOrder;
use App\Models\Location;
use App\Models\Order;
use App\Models\Region;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JetOrderController extends BaseApiController
{

    public function index(Request $request, $restaurant)
    {

        $branch = Branch::find($restaurant);

        if (empty($branch)) {
            return $this->respondNotFound();
        }

        $previousOrders = JetOrder::where('branch_id', $branch->id)->latest()->get();


        if ( ! is_null($previousOrders)) {
            return $this->respond(JetOrderResource::collection($previousOrders));
        }

        return $this->respondNotFound();
    }


    public function show($restaurant, JetOrder $order)
    {

        return $this->respond([
            'order' => new JetOrderResource($order),
        ]);
    }

    public function destroy($id)
    {
        $order = JetOrder::find($id);

        if (is_null($order)) {
            return $this->respondNotFound();
        } elseif ($order->delete()) {
            return $this->respond([
                'type' => 'success',
                'text' => 'Successfully Deleted',
            ]);
        }

        return $this->respond([
            'type' => 'error',
            'text' => 'There seems to be a problem',
        ]);
    }


    public function create(Request $request, $restaurant)
    {

        $branch = Branch::findOrFail($restaurant);
        $regions = Region::where('id', config('defaults.region.id'))->get();
        $cities = City::active()->whereRegionId(config('defaults.region.id'))->get();

        return $this->respond(
            [
                'regions' => RegionResource::collection($regions),
                'cities' => CityResource::collection($cities),
            ]
        );
    }
    public function getDeliveryFee(Request $request, $restaurant)
    {

        $branch = Branch::findOrFail($restaurant);

        $validationRules = [
            'latitude' => 'required',
            'longitude' => 'required',
            'total' => 'required|numeric',
        ];


        $validator = validator()->make($request->all(), $validationRules);
        if ($validator->fails()) {
            return $this->respondValidationFails($validator->errors());
        }

        $deliveryFees = $branch->calculateJetDeliveryFee($request->input('total'), $request->input('latitude'),
            $request->input('longitude'));

        return $this->respond(
            [
                'delivery_fees' =>  [
                    'raw' => $deliveryFees,
                    'formatted' => Currency::format($deliveryFees),
                ]
            ]
        );
    }

    public function store(Request $request, $restaurant)
    {
        $validationRules = [
            'city_id' => 'required',
            'address' => 'required|min:10',
            'latitude' => 'required',
            'longitude' => 'required',
            'total' => 'required|numeric',
            'phone' => 'required|numeric|digits_between:7,15',
            'full_name' => 'required|min:3',
            'images.*' => 'sometimes|image|mimes:jpeg,png,jpg|max:5000'
        ];

        $branch = Branch::findOrFail($restaurant);


        $validator = validator()->make($request->all(), $validationRules);
        if ($validator->fails()) {
            return $this->respondValidationFails($validator->errors());
        }

        DB::beginTransaction();

        $deliveryFee = $branch->calculateJetDeliveryFee($request->input('total'), $request->input('latitude'),
            $request->input('longitude'));

        $newOrder = new JetOrder();
        $newOrder->chain_id = $branch->chain_id;
        $newOrder->branch_id = $restaurant;
        $newOrder->city_id = $request->input('city_id');
        $newOrder->destination_full_name = $request->input('full_name');
        $newOrder->destination_address = $request->input('address');
        $newOrder->destination_phone = $request->input('phone');
        $newOrder->total = $request->input('total');
        $newOrder->grand_total = $request->input('total');
        $newOrder->destination_latitude = $request->input('latitude');
        $newOrder->destination_longitude = $request->input('longitude');
        $newOrder->client_notes = $request->input('notes');


        $newOrder->delivery_fee = $deliveryFee;
        $newOrder->status = JetOrder::STATUS_DRAFT;
        $newOrder->save();

        if ($request->has('images')) {
            $images = $request->file('images');
            foreach ($images as $image) {
                $newOrder->addMedia($image)->toMediaCollection('gallery');

            }

        }

        $newOrder->status = JetOrder::STATUS_ASSIGNING_COURIER;
        $newOrder->save();

        DB::commit();

        return $this->respond(new JetOrderResource($newOrder));
    }

}
