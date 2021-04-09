<?php

namespace App\Http\Controllers\Api\V1;


use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\BranchResource;
use App\Http\Resources\CityResource;
use App\Http\Resources\FoodBranchResource;
use App\Http\Resources\ProductResource;
use App\Http\Resources\RegionResource;
use App\Http\Resources\UserResource;
use App\Models\Branch;
use App\Models\City;
use App\Models\Product;
use App\Models\Region;
use App\Models\User;
use DB;
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
        if (is_null($user)) {
            return $this->respondNotFound();
        }
        $validationRules = [
            'full_name' => 'required|min:3|max:60',
            'email' => 'nullable|email|min:3|max:255|unique:users,email,'.$user->id,
        ];

        $validator = validator()->make($request->all(), $validationRules);
        if ($validator->fails()) {
            return $this->respondValidationFails($validator->errors());
        }

        DB::beginTransaction();
        [$user->first, $user->last] = User::extractFirstAndLastNames($request->full_name);
        if ($request->email) {
            $user->email = $request->email;
            $user->username = strstr($request->email, '@', 1);
        }
        $user->region_id = $request->region_id;
        $user->city_id = $request->city_id;

        if ($request->has('settings') && $request->input('settings')) {
            $user->settings = json_decode($request->settings);
        }
        $user->save();

        if ($request->hasFile('avatar')) {
            $user->addMediaFromRequest('avatar')
                 ->toMediaCollection('avatar');
        }

        DB::commit();

        return $this->respond([
            'user' => new UserResource($user),
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

    public function interact($productID, Request $request)
    {
        $product = Product::find($productID);
        $user = auth()->user();
        switch ($request->action) {
            case 'favorite':
                if ( ! $user->hasFavorited($product)) {
                    $user->favorite($product);

                    return $this->respondWithMessage(__('api.product_added_to_wishlist_successfully'));
                }
                break;
            case 'unfavorite':
                if ($user->hasFavorited($product)) {
                    $user->unfavorite($product);

                    return $this->respondWithMessage(__('api.product_removed_from_wishlist_successfully'));
                }
                break;
        }

        return $this->respondValidationFails(__('api.interaction_failed_please_check_parameters'));
    }

    public function favorites()
    {
        $user = auth()->user();
        if (auth()->check()) {
            return $this->respond([
                'products' => ProductResource::collection($user->favorites(Product::class)->get()),
            ]);
        }

        return $this->respondNotFound();
    }


    public function foodInteract($restaurantId, Request $request)
    {
        $branch = Branch::find($restaurantId);
        $user = auth()->user();
        switch ($request->action) {
            case 'favorite':
                if ( ! $user->hasFavorited($branch)) {

                    $user->favorite($branch);

                    return $this->respondWithMessage(__('api.restaurant_added_to_wishlist_successfully'));
                }
                break;
            case 'unfavorite':
                if ($user->hasFavorited($branch)) {
                    $user->unfavorite($branch);

                    return $this->respondWithMessage(__('api.restaurant_removed_from_wishlist_successfully'));
                }
                break;
        }

        return $this->respondValidationFails(__('api.interaction_failed_please_check_parameters'));
    }

    public function foodFavorites()
    {
        $user = auth()->user();
        if (auth()->check()) {
            return $this->respond([
                'restaurants' => FoodBranchResource::collection($user->favorites(Branch::class)->get()),
            ]);
        }

        return $this->respondNotFound();
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
