<?php

namespace App\Http\Controllers\Api\V1;


use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\LocationResource;
use App\Models\Branch;
use App\Models\Location;
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

        return LocationResource::collection($addresses);
    }

    public function create(Request $request)
    {
        $data = $this->essentialData();

        return $this->respond($data);

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
        $user_id = auth()->user()->id;
        $address = new Location();
        $address->creator_id = $user_id;
        $address->editor_id = $user_id;
        $address->country_id = isset($request->country_id) ? $request->country_id : config('defaults.country.id');
        $address->region_id = isset($request->region_id) ? $request->region_id : config('defaults.region.id');
        $address->contactable_type = User::class;
        $address->contactable_id = $user_id;

        $address->city_id = isset($request->city_id) ? $request->city_id : config('defaults.city.id');
        $address->kind = $request->kind;
        $address->name = $request->name;
        $address->building = $request->building;
        $address->floor = $request->floor;
        $address->apartment = $request->flat;
        $address->latitude = $request->latitude;
        $address->longitude = $request->longitude;
        $address->notes = $request->directions;
        $address->save();

        \DB::commit();

        return $this->respondWithMessage(__('strings.successfully_updated'));
    }

    public function destroy($address)
    {

        $address = Location::find($address);
        if (is_null($address)) {
            return $this->respondNotFound();
        } elseif ($address->delete()) {
            return $this->respondWithMessage(__('strings.successfully_deleted'));
        }

        return $this->respond([
            'type' => 'error',
            'text' => 'There seems to be a problem',
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

    private function essentialData()
    {
        return [
            'kinds' => Location::getKinds()
        ];
    }

}
