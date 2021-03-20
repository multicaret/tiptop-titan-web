<?php

namespace App\Http\Controllers\Api\V1;


use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\CityResource;
use App\Http\Resources\RegionResource;
use App\Http\Resources\UserResource;
use App\Models\City;
use App\Models\Location;
use App\Models\Region;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends BaseApiController
{
    public function index()
    {
        $builder = User::newModelInstance();

        $builder->orderByDesc('created_at');

        if (request()->has('order') && ! empty(request()->get('order'))) {
            foreach (request()->get('order') as $orderColumn => $orderValue) {
                $builder->where($orderColumn, $orderValue);
            }
        }

        return UserResource::collection($builder->get());
    }

    public function create(Request $request)
    {
        $data = $this->essentialData();

//        $user = new User();
//        $user->status = User::STATUS_ACTIVE;
//        $user->country_id = config('defaults.country.id');
//        $user->region_id = config('defaults.region.id');
//        $user->city_id = config('defaults.city.id');
//        $user->load(['country', 'region', 'city']);

//        $data['user'] = $user;

        return $this->respond($data);

    }

    public function store(Request $request)
    {
        \DB::beginTransaction();
        $request->validate([
            'first' => 'required|min:3|max:60',
            'email' => 'required|email|min:3|max:255|unique:users,email',
//            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = new User();
        $user->first = $request->first;
        $user->last = $request->last;
        $user->gender = $request->gender;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->username = strstr($request->email, '@', 1);
        $user->status = isset($request->status) ? $request->status : User::STATUS_ACTIVE;
        $user->role = isset($request->role) ? $request->role : User::ROLE_USER;
        $user->country_id = isset($request->country_id) ? $request->country_id : config('defaults.country.id');
        $user->region_id = isset($request->region_id) ? $request->region_id : config('defaults.region.id');
        $user->city_id = isset($request->city_id) ? $request->city_id : config('defaults.city.id');
        $user->save();

        $address = new Location();
        $address->creator_id = $address->editor_id = auth()->id();
        $address->contactable_id = $user->id;
        $address->contactable_type = User::class;
        $address->country_id = $request->country_id;
        $address->region_id = $request->region_id;
        $address->city_id = $request->city_id;
        $address->name = $user->name;
        $address->address1 = $request->address1;
        $address->address2 = $request->address2;
        $address->emails = $request->emails;
        $address->phones = $request->phones;
        $address->save();

        if ($request->send_notification) {
            $this->sendEmail($user, 'Welcome', [$user, $request->password]);
        }

        if ($request->hasFile('avatar')) {
            $user->addMediaFromRequest('avatar')
                 ->toMediaCollection('avatar');
        }
        \DB::commit();

        return $this->respond([
            'success' => true,
            'message' => 'Successfully Stored',
        ]);
    }

    public function edit()
    {
        $user = auth()->user();
        $data = $this->essentialData();
        $user = new UserResource($user);
        $data['user'] = $user;

        return $this->respond($data);
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $validationRules = [
            'full_name' => 'required|min:5|max:60',
            'email' => 'email|min:3|max:255|unique:users,email,'.$user->id,
        ];

        $request->validate($validationRules);
        [$user->first, $user->last] = User::extractFirstAndLastNames($request->full_name);
        if ($request->email) {
            $user->email = $request->email;
            $user->username = strstr($request->email, '@', 1);
        }
        $user->region_id = $request->region_id;
        $user->city_id = $request->city_id;
        $user->save();

        if ($request->hasFile('avatar')) {
            $user->addMediaFromRequest('avatar')
                 ->toMediaCollection('avatar');
        }

        return $this->respond([
            'success' => true,
            'message' => 'Successfully Updated',
        ]);
    }

    public function destroy($user)
    {

        $user = User::find($user);

        if ($user->delete()) {
            return $this->respond([
                'type' => 'success',
                'text' => 'The user has been deleted',
            ]);
        }

        return $this->respond([
            'type' => 'error',
            'text' => 'There seems to be a problem',
        ]);
    }

    private function essentialData()
    {
        return [
            'regions' => RegionResource::collection(
                Region::getAllOfCountry(config('defaults.country.id'))
            ),
            'cities' => CityResource::collection(
                City::getAllOfRegion(config('defaults.region.id'))
            ),
        ];
    }
}
