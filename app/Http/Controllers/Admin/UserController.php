<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Country;
use App\Models\Region;
use App\Models\User;
use DB;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:user.permissions.index', ['only' => ['index', 'store']]);
        $this->middleware('permission:user.permissions.create', ['only' => ['create', 'store']]);
        $this->middleware('permission:user.permissions.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:user.permissions.destroy', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  Request  $request
     *
     * @return Response
     */
    public function index($type)
    {
        return view('admin.users.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param $type
     * @param  Request  $request
     *
     * @return Response
     */
    public function create($type, Request $request)
    {
        $data = $this->essentialData();

        $user = new User();
        $user->status = User::STATUS_ACTIVE;
        $user->country_id = config('defaults.country.id');
        $user->region_id = config('defaults.region.id');
        $user->city_id = config('defaults.city.id');
        $user->load(['country', 'region', 'city']);

        $data['user'] = $user;
        $type = $request->type;
        $data['role'] = $this->getRoleFromUserType($type);
        $data['permissions'] = config('defaults.all_permission.super');

        return view('admin.users.form', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     *
     * @return RedirectResponse
     */
    public function store($type, Request $request)
    {
        $request->validate([
            'first' => 'required|min:3|max:60',
            'email' => 'required|email|min:3|max:255|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);
        $previousOrderValue = User::orderBy('order_column', 'ASC')->first();
        $order = is_null($previousOrderValue) ? 1 : $previousOrderValue->order_column + 1;
        $role = $request->type;

        DB::beginTransaction();
        $user = new User();
        $user->first = $request->first;
        $user->last = $request->last;
        $user->gender = $request->gender;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->username = strstr($request->email, '@', 1);
        $user->status = $request->status;
        $user->country_id = $request->country_id;
        $user->region_id = $request->region_id;
        $user->city_id = $request->city_id;
        $user->order_column = $order;

        $user->save();

        /*$address = new Location();
        $address->creator_id = $user->id;
        $address->editor_id = $user->id;
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
        $address->save();*/

        if ($request->send_notification) {
            $this->sendEmail($user, 'Welcome', [$user]);
        }

        $this->handleSubmittedSingleMedia('avatar', $request, $user);

        DB::commit();

        $roleName = $this->getRoleFromUserType($role)->name;
        $user->assignRole($roleName);

        return redirect()
            ->route('admin.users.index', ['type' => $request->type])
            ->with('message', [
                'type' => 'Success',
                'text' => 'Successfully Created'
            ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Request  $request
     * @param  User  $user
     *
     * @return Response
     */
    public function edit($type, User $user, Request $request)
    {
        if (is_null($type)) {
            dd('type is null in edit');
        }
        $type = $request->type;
        $data = $this->essentialData();
        $user->load(['country', 'region', 'city']);
        $data['user'] = $user;
        $data['role'] = $this->getRoleFromUserType($type);
        $data['permissions'] = config('defaults.all_permission.super');

        return view('admin.users.form', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param $type
     * @param  User  $user
     *
     * @param  Request  $request
     * @return RedirectResponse
     */
    public function update($type, User $user, Request $request)
    {
        if (is_null($type)) {
            dd('type is null in edit');
        }
        $validationRules = [
            'first' => 'required|min:3|max:60',
            'email' => 'required|email|min:3|max:255|unique:users,email,'.$user->id,
        ];

        if ( ! empty($request->password)) {
            $validationRules['password'] = 'required|string|min:6|confirmed';
        }

        $request->validate($validationRules);

        DB::beginTransaction();
        $user->first = $request->first;
        $user->last = $request->last;
        $user->gender = $request->gender;
        $user->email = $request->email;
        $user->status = $request->status;
        $user->country_id = $request->country_id;
        $user->region_id = $request->region_id;
        $user->city_id = $request->city_id;
        $user->save();

        if ( ! empty($request->password)) {
            $user->password = bcrypt($request->password);
        }

        /*if (is_null($address = Location::where('contactable_id', $user->id)
                                       ->where('contactable_type', User::class)
                                       ->whereNull('alias')
                                       ->first())) {
            $address = new Location();
            $address->creator_id = $address->editor_id = auth()->id();
            $address->contactable_id = $user->id;
            $address->contactable_type = User::class;
        }
        $address->editor_id = auth()->id();
        $address->editor_id = $user->id;
        $address->country_id = $request->country_id;
        $address->region_id = $request->region_id;
        $address->city_id = $request->city_id;
        $address->name = $user->name;
        $address->address1 = $request->address1;
        $address->address2 = $request->address2;
        $address->emails = $request->emails;
        $address->phones = $request->phones;
        $address->save();*/

        $this->handleSubmittedSingleMedia('avatar', $request, $user);
        DB::commit();

        $roleName = $this->getRoleFromUserType($request->type)->name;
        $user->assignRole($roleName);
        if (auth()->user()->hasRole([User::ROLE_SUPER, User::ROLE_ADMIN])) {
            $permissions = array_keys($request->input('permissions', []));
            $user->syncPermissions($permissions);
        }

        return redirect()
            ->route('admin.users.index', ['type' => $request->type])
            ->with('message', [
                'type' => 'Success',
                'text' => 'Successfully Updated'
            ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  User  $user
     *
     * @return Response
     */
    public function destroy($type, User $user)
    {
        if ($user->delete()) {
            return back()->with('message', [
                'type' => 'Success',
                'text' => 'The user has been deleted',
            ]);
        }

        return back()->with('message', [
            'type' => 'Error',
            'text' => 'Seems to have gotten a problem',
        ]);
    }

    private function essentialData()
    {
        return [
            'roles' => [
//                User::ROLE_SUPER => trans('strings.' . User::ROLE_SUPER),
                User::ROLE_ADMIN => trans('strings.'.User::ROLE_ADMIN),
                User::ROLE_CONTENT_EDITOR => trans('strings.'.User::ROLE_CONTENT_EDITOR),
            ],
            'countries' => Country::all(),
            'regions' => Region::where('country_id', config('defaults.country.id'))->get(),
            'cities' => City::where('region_id', config('defaults.region.id'))->get(),
        ];
    }

    private function getRoleFromUserType($type): Role
    {
        $tempRoleName = str_replace('-', ' ', $type);

        return Role::findByName(ucwords($tempRoleName));
    }

    private function checkPermissionsIfUpdated(array $permissions, string $roleName): bool
    {
        $role = Role::findByName($roleName);
        $rolePermissions = $role->permissions->pluck('name')->toArray();
        $tempPermissions = array_intersect($rolePermissions, $permissions);

        return count($rolePermissions) !== count($tempPermissions);
    }

}
