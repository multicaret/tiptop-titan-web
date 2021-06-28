<?php

namespace App\Http\Controllers\Api\V1;


use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\CityResource;
use App\Http\Resources\LocationResource;
use App\Http\Resources\RegionResource;
use App\Models\Branch;
use App\Models\City;
use App\Models\Location;
use App\Models\Order;
use App\Models\Region;
use App\Models\User;
use DB;
use Illuminate\Http\Request;

class AddressController extends BaseApiController
{

    public function index()
    {
        $user = auth()->user();
        // authenticated but not found? is this even possible dear Laravel?
        if (is_null($user)) {
            return $this->respondNotFound();
        }
        $addresses = $user->addresses()->get();
        $this->respond($addresses);

        return $this->respond([
            'addresses' => LocationResource::collection($addresses),
            'kinds' => array_values(Location::getKindsForMaps()),
        ]);
    }

    public function create(Request $request)
    {
        // TODO: get the nearest address to this latitude longitude
//        $latitude = $request->input('latitude');
//        $longitude = $request->input('longitude');

        $regions = Region::active()
                         ->where('regions.id', config('defaults.region.id'))
                         ->orderByTranslation('name')
                         ->get();
        $cities = City::active()
                      ->whereRegionId(config('defaults.region.id'))
                      ->orderByTranslation('name')
                      ->get();

        $selectedRegion = Region::find(config('defaults.region.id'));
        $selectedCity = City::whereRegionId($selectedRegion->id)->first();

        return $this->respond(
            [
                'regions' => RegionResource::collection($regions),
                'cities' => CityResource::collection($cities),
                'selectedRegion' => new RegionResource($selectedRegion),
                'selectedCity' => new CityResource($selectedCity),
                'kinds' => array_values(Location::getKindsForMaps()),
            ]
        );

    }

    public function store(Request $request)
    {
        $rules = [
            'alias' => 'required',
            'region_id' => 'required',
            'city_id' => 'required',
            'address1' => 'required',
            'phone_number' => 'required',
        ];

        $validator = validator()->make($request->all(), $rules);
        if ($validator->fails()) {
            return $this->respondValidationFails($validator->errors());
        }

        DB::beginTransaction();
        $userId = auth()->user()->id;
        $address = new Location();
        $address->contactable_type = User::class;
        $address->contactable_id = $userId;
        $address->creator_id = $userId;
        $address->editor_id = $userId;
        $address->country_id = $request->country_id ?? config('defaults.country.id');
        $address->region_id = $request->region_id;
        $address->city_id = $request->city_id;
        $address->kind = $request->kind;
        $address->address1 = $request->address1;
        $address->alias = $request->alias;
        $address->phones = [$request->phone_number];
//        $address->building = $request->building;
//        $address->floor = $request->floor;
//        $address->apartment = $request->flat;
        $address->latitude = $request->latitude;
        $address->longitude = $request->longitude;
        $address->notes = $request->notes;
        $address->save();

        DB::commit();

        return $this->respond([
            'address' => new LocationResource($address)
        ]);
    }

    public function destroy($address)
    {
        $address = Location::find($address);
        $user = auth()->user();
        $hasMoreThanOneAddress = Location::whereContactableType(User::class)->whereContactableId($user->id)->count() > 1;
        $canDeleteTheirAddress = $address->contactable_id == $user->id && $hasMoreThanOneAddress;
        if ( ! $canDeleteTheirAddress) {
            return $this->respondValidationFails([
                'address' => __('strings.You can not delete this address, please add a new one before deleting this address')
            ]);
        }
        if (is_null($address)) {
            return $this->respondNotFound();
        } elseif (Order::whereAddressId($address->id)->count()) {
            return $this->respondWithMessage(__('strings.You have orders with this address you can not delete it'));
        } elseif ($address->delete()) {
            return $this->respondWithMessage(__('strings.successfully_deleted'));
        }

        return $this->respondValidationFails([
            'address' => 'There seems to be a problem'
        ]);
    }

    public function changeSelectedAddress(Request $request)
    {
        $response = [
            'distance' => null,
            'branch' => null,
            'hasAvailableBranchesNow' => false,
            'estimated_arrival_time' => [
                'value' => '30-45',
                'unit' => 'min',
            ],
        ];

        [$distance, $branch] = Branch::getClosestAvailableBranch($request->latitude, $request->longitude);
        if ( ! is_null($distance)) {
            $response['estimated_arrival_time']['value'] = '20-30';
            $response['distance'] = $distance;
            $response['branch'] = $branch;
            $response['hasAvailableBranchesNow'] = true;

            return $this->respond($response);
        }

        return $this->respond([
            'type' => 'error',
            'text' => 'There seems to be a problem',
        ]);
    }

    /*  private function essentialData()
      {
          return [
              'kinds' => Location::getKinds()
          ];
      }*/

}
