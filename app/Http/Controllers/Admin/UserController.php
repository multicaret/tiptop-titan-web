<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\City;
use App\Models\Country;
use App\Models\Location;
use App\Models\Region;
use App\Models\TookanTeam;
use App\Models\User;
use DB;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{

    public function __construct()
    {
        $userType = request()->segment(3);
        $this->middleware('permission:'.$userType.'.permissions.index', ['only' => ['index', 'store']]);
        $this->middleware('permission:'.$userType.'.permissions.create', ['only' => ['create', 'store']]);
        $this->middleware('permission:'.$userType.'.permissions.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:'.$userType.'.permissions.destroy', ['only' => ['destroy']]);
    }

    public function index(Request $request, $role)
    {
        $this->roleValidation($role);
        $columns = [
            [
                'data' => 'first',
                'name' => 'first',
                'title' => trans('strings.first_name'),
            ],
            [
                'data' => 'last',
                'name' => 'last',
                'title' => trans('strings.last_name'),
            ],
            [
                'data' => 'phone_number',
                'name' => 'phone_number',
                'visible' => false,
            ],
            [
                'data' => 'username',
                'name' => 'username',
                'title' => trans('strings.username'),
            ],
            [
                'data' => 'email',
                'visible' => false,
                'searchable' => true,
            ],
            /*[
                'data' => 'email',
                'name' => 'email',
                'title' => trans('strings.email'),
            ],*/

        ];
        /*if ($role == User::ROLE_RESTAURANT_DRIVER) {
            $columns = array_merge($columns, [
                [
                    'data' => 'branch',
                    'name' => 'branch',
                    'title' => trans('strings.branch'),
                    'orderable' => false,
                    'searchable' => false
                ]
            ]);
        }*/
        if ($role == User::ROLE_TIPTOP_DRIVER) {
            $columns = array_merge($columns, [
                [
                    'data' => 'employment',
                    'name' => 'employment',
                    'title' => trans('strings.employment'),
                    'orderable' => false,
                    'searchable' => false
                ]
            ]);
        }

        $columns = array_merge($columns, [
            [
                'data' => 'status',
                'name' => 'status',
                'title' => 'Status',
            ],
        ]);
        if ($role != User::ROLE_TIPTOP_DRIVER) {
            $columns = array_merge($columns, [
                [
                    'data' => 'created_at',
                    'name' => 'created_at',
                    'title' => trans('strings.create_date')
                ],
            ]);
        } else {
            $columns = array_merge($columns, [
                [
                    'data' => 'team',
                    'name' => 'team',
                    'title' => trans('strings.team'),
                    'orderable' => false,
                    'searchable' => false
                ],
            ]);
        }

        return view('admin.users.index', compact('columns', 'role'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param $role
     * @param  Request  $request
     *
     * @return Response
     */
    public function create($role, Request $request)
    {
        $this->roleValidation($role);
        $data = $this->essentialData($role);
        $roleName = $this->getRoleName($role);
        $user = new User();
        $user->status = User::STATUS_ACTIVE;
        $user->country_id = config('defaults.country.id');
        $user->region_id = config('defaults.region.id');
        $user->city_id = config('defaults.city.id');
        $user->load(['country', 'region', 'city']);

        $data['user'] = $user;
        $data['role'] = $role;
        $data['roleName'] = $roleName;
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
    public function store($role, Request $request)
    {
        $this->roleValidation($role);
        $request->validate([
            'first' => 'required|min:3|max:60',
            'email' => 'required|email|min:3|max:255|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'phone' => 'required|numeric|digits_between:7,15|unique:users,phone_number',
        ]);
        $previousOrderValue = User::orderBy('order_column', 'ASC')->first();
        $order = is_null($previousOrderValue) ? 1 : $previousOrderValue->order_column + 1;

        DB::beginTransaction();
        $user = new User();
        $user->first = $request->first;
        $user->last = $request->last;
        $user->gender = $request->gender;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->username = strstr($request->email, '@', 1).'-'.strtolower(Str::random(4));
        $user->status = $request->status;
        $user->country_id = $request->country_id;
        $user->region_id = $request->region_id;
        $user->city_id = $request->city_id;
        $user->employment = $request->employment;
        $user->team_id = $request->team_id;
        $user->branch_id = $request->branch_id;
        $user->order_column = $order;
        $user->phone_country_code = '964';
        $user->phone_number = $request->phone;

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


        if (in_array($role, User::rolesHaving('branches'))) {
            $user->branches($role)->sync($request->input('branches'));
        }

        $roleName = $this->getRoleName($role);
        $user->assignRole($roleName);
        DB::commit();

        return redirect()
            ->route('admin.users.index', ['role' => $role])
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
    public function edit($role, User $user, Request $request)
    {
        $role = Str::kebab($user->role_name);
        $data = $this->essentialData($role);
        $user->load(['country', 'region', 'city']);
        $data['user'] = $user;
        $data['role'] = $role;
        $data['roleName'] = $user->role_name;
        $data['permissions'] = config('defaults.all_permission.super');
        if (in_array($role, User::rolesHaving('branches'))) {
            $data['selectedBranches'] = $user->branches($role)->get();
        }

        return view('admin.users.form', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param $role
     * @param  User  $user
     *
     * @param  Request  $request
     * @return RedirectResponse
     */
    public function update($role, User $user, Request $request)
    {
        $role = Str::kebab($user->role_name);

        $validationRules = [
            'first' => 'required|min:3|max:60',
            'email' => 'required|email|min:3|max:255|unique:users,email,'.$user->id,
            'phone' => 'required|numeric|digits_between:7,15'.$user->phone_number,
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
        $user->employment = $request->employment;
        $user->team_id = $request->team_id;
        $user->branch_id = $request->branch_id;
        $user->phone_number = $request->phone;
        $user->save();

        if ( ! empty($request->password)) {
            $user->password = bcrypt($request->password);
        }

        /*if (is_null($address = Location::where('contactable_id', $user->id)
                                       ->where('contactable_role', User::class)
                                       ->whereNull('alias')
                                       ->first())) {
            $address = new Location();
            $address->creator_id = $address->editor_id = auth()->id();
            $address->contactable_id = $user->id;
            $address->contactable_role = User::class;
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

        if (in_array($role, User::rolesHaving('branches'))) {
            $user->branches($role)->sync($request->input('branches'));
        }

        $roleName = $this->getRoleName($user->role_name);
        $user->assignRole($roleName);
        if (auth()->user()->hasRole([User::ROLE_SUPER, User::ROLE_ADMIN])) {
            $permissions = array_keys($request->input('permissions', []));
            $user->syncPermissions($permissions);
        }

        return redirect()
            ->route('admin.users.index', ['role' => $request->role])
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
    public function destroy($role, User $user)
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

    private function essentialData($role)
    {
        $branchData['hasBranch'] = request()->has('branch_id');
        $branchExists = ! is_null(\App\Models\Branch::all()->find(request()->input('branch_id')));
        $branches = [];
        if ($branchData['hasBranch']) {
            if ($branchExists) {
                $branchData['branchId'] = request()->input('branch_id');
            } else {
                abort(404);
            };
        }
        if (in_array($role, User::rolesHaving('branches'))) {
            $branchChannel = Branch::find($branchData['branchId'])->type == Branch::CHANNEL_FOOD_OBJECT ? Branch::CHANNEL_FOOD_OBJECT : Branch::CHANNEL_GROCERY_OBJECT;
            $branches = Branch::whereType($branchChannel)
                              ->active()
                              ->get()
                              ->mapWithKeys(function ($item) {
                                  return [$item['id'] => $item['chain']['title'].' - '.$item['title'].' ('.$item['region']['english_name'].', '.$item['city']['english_name'].')'];
                              });
        }

        return [
            'roles' => [
//                User::ROLE_SUPER => trans('strings.' . User::ROLE_SUPER),
                User::ROLE_ADMIN => trans('strings.'.User::ROLE_ADMIN),
                User::ROLE_CONTENT_EDITOR => trans('strings.'.User::ROLE_CONTENT_EDITOR),
            ],
            'countries' => Country::all(),
            'regions' => Region::where('country_id', config('defaults.country.id'))->get(),
            'teams' => TookanTeam::active()->get()
                                 ->mapWithKeys(function ($item) {
                                     return [$item['id'] => $item['name']];
                                 }),
            'cities' => City::where('region_id', config('defaults.region.id'))->get(),
            'branchData' => $branchData,
            'branches' => $branches,
        ];
    }

    private function getRoleName($role)
    {
        $tempRoleName = str_replace('-', ' ', $role);

        return Role::findByName(ucwords($tempRoleName))->name;
    }

    private function checkPermissionsIfUpdated(array $permissions, string $roleName): bool
    {
        $role = Role::findByName($roleName);
        $rolePermissions = $role->permissions->pluck('name')->toArray();
        $tempPermissions = array_intersect($rolePermissions, $permissions);

        return count($rolePermissions) !== count($tempPermissions);
    }

    private function roleValidation(string $role)
    {
        if ( ! in_array($role, User::getAllRoles()) || $role == User::ROLE_SUPER) {
            return abort(404);
        }
    }

    public function editAddress(User $user, Location $address, Request $request)
    {
        $role = Str::kebab($user->role_name);
        if ($role != User::ROLE_USER || $address->contactable_id != $user->id) {
            return abort(Response::HTTP_NOT_FOUND);
        }
        $regions = Region::whereCountryId(config('defaults.country.id'))->get();
        $kinds = array_values(Location::getKindsForMaps());

        return view('admin.users.address-form', compact(['user', 'address', 'regions', 'kinds']));
    }

    public function updateAddress(User $user, Location $address, Request $request)
    {
        $validationRules = [
            'alias' => 'required',
            'region_id' => 'required',
            'city_id' => 'required',
            'address1' => 'required',
        ];

//        $request->validate($validationRules);

        DB::beginTransaction();
        $userId = $user->id;
        $address->editor_id = $userId;
        $address->country_id = $request->country_id ?? config('defaults.country.id');
        $address->region_id = optional(json_decode($request->input('region')))->id;
        $address->city_id = optional(json_decode($request->input('city')))->id;
        $address->kind = optional(json_decode($request->input('kind')))->id;
        $address->alias = $request->alias;
        $address->address1 = $request->address1;
        $address->phones = $request->phone_number;
//        $address->building = $request->building;
//        $address->floor = $request->floor;
//        $address->apartment = $request->flat;
        $address->latitude = $request->latitude;
        $address->longitude = $request->longitude;
        $address->notes = $request->notes;
        $address->save();

        DB::commit();

        return redirect()
            ->route('admin.users.edit', ['role' => $user->role_name, 'user' => $user])
            ->with('message', [
                'type' => 'Success',
                'text' => 'Successfully Updated'
            ]);
    }

}
