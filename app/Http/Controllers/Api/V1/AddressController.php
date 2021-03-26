<?php

namespace App\Http\Controllers\Api\V1;


use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\CityResource;
use App\Http\Resources\LocationResource;
use App\Http\Resources\RegionResource;
use App\Models\Branch;
use App\Models\City;
use App\Models\Location;
use App\Models\Region;
use App\Models\User;
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
            'kinds' => Location::getKindsForMaps(),
        ]);
    }

    public function create(Request $request)
    {
        // TODO: get the nearest address to this latitude longitude
//        $latitude = $request->input('latitude');
//        $longitude = $request->input('longitude');

        $regions = Region::whereCountryId(config('defaults.country.id'))->get();
        $cities = City::whereCountryId(config('defaults.country.id'))->get();


        $selectedRegion = Region::whereCountryId(config('defaults.country.id'))->skip(1)->first();
        $selectedCity = City::whereRegionId($selectedRegion->id)->first();

        return $this->respond(
            [
                'regions' => RegionResource::collection($regions),
                'cities' => CityResource::collection($cities),
                'selectedRegion' => new RegionResource($selectedRegion),
                'selectedCity' => new CityResource($selectedCity),
                'kinds' => Location::getKindsForMaps(),
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
        ];

        $validator = validator()->make($request->all(), $rules);
        if ($validator->fails()) {
            return $this->respondValidationFails($validator->errors());
        }

        \DB::beginTransaction();
        $userId = auth()->user()->id;
        $address = new Location();
        $address->creator_id = $userId;
        $address->editor_id = $userId;
        $address->country_id = isset($request->country_id) ? $request->country_id : config('defaults.country.id');
        $address->region_id = $request->input('region_id');
        $address->city_id = $request->input('city_id');
        $address->contactable_type = User::class;
        $address->contactable_id = $userId;
        $address->kind = $request->kind;
        $address->address1 = $request->address1;
        $address->alias = $request->alias;
//        $address->building = $request->building;
//        $address->floor = $request->floor;
//        $address->apartment = $request->flat;
        $address->latitude = $request->latitude;
        $address->longitude = $request->longitude;
        $address->notes = $request->directions;
        $address->save();

        \DB::commit();

        return $this->respond([
            'address' => new LocationResource($address)
        ]);
    }

    public function destroy($address)
    {

        $address = Location::find($address);
        if (is_null($address)) {
            return $this->respondNotFound();
        } elseif ($address->delete()) {
            return $this->respondWithMessage(__('strings.successfully_deleted'));
        }

        return $this->respondValidationFails('There seems to be a problem');
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
