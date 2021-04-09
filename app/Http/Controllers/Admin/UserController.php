<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\City;
use App\Models\Country;
use App\Models\Region;
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
        $this->middleware('permission:user.permissions.index', ['only' => ['index', 'store']]);
        $this->middleware('permission:user.permissions.create', ['only' => ['create', 'store']]);
        $this->middleware('permission:user.permissions.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:user.permissions.destroy', ['only' => ['destroy']]);
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
                'data' => 'username',
                'name' => 'username',
                'title' => trans('strings.username'),
            ],
            /*[
                'data' => 'email',
                'name' => 'email',
                'title' => trans('strings.email'),
            ],*/

        ];
        if ($role == User::ROLE_RESTAURANT_DRIVER) {
            $columns = array_merge($columns, [
                [
                    'data' => 'branch.title',
                    'name' => 'branch.title',
                    'title' => trans('strings.branch'),
                    'orderable' => false,
                    'searchable' => false
                ]
            ]);
        }
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
            [
                'data' => 'created_at',
                'name' => 'created_at',
                'title' => trans('strings.create_date')
            ],
            [
                'data' => 'last_logged_in_at',
                'name' => 'last_logged_in_at',
                'title' => __('Last logged In')
            ],
        ]);

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
        $data = $this->essentialData();
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
        $user->username = strstr($request->email, '@', 1);
        $user->status = $request->status;
        $user->country_id = $request->country_id;
        $user->region_id = $request->region_id;
        $user->city_id = $request->city_id;
        $user->employment = $request->employment;
        $user->branch_id = $request->branch_id;
        $user->tokan_team = $request->tokan_team;
        $user->tokan_id = $request->tokan_id;
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

        $roleName = $this->getRoleName($role);
        $user->assignRole($roleName);

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
    public function edit(User $user, Request $request)
    {
        $roleName = $user->role->name;
        $role = Str::kebab($roleName);
        $data = $this->essentialData();
        $user->load(['country', 'region', 'city']);
        $data['user'] = $user;
        $data['role'] = $role;
        $data['roleName'] = $roleName;
        $data['permissions'] = config('defaults.all_permission.super');

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
    public function update(User $user, Request $request)
    {
        $roleName = $user->role->name;
        $role = Str::kebab($roleName);

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
        $user->employment = $request->employment;
        $user->branch_id = $request->branch_id;
        $user->tokan_team = $request->tokan_team;
        $user->tokan_id = $request->tokan_id;
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
    public function destroy(User $user)
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
            'branches' => Branch::whereType(Branch::CHANNEL_FOOD_OBJECT)
                                ->active()
                                ->get()
                                ->mapWithKeys(function ($item) {
                                    return [$item['id'] => $item['title'].' - '.$item['chain']['title'].' ('.$item['city']['english_name'].')'];
                                }),
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
        if ( ! in_array($role, User::getAllRoles())) {
            return abort(404);
        }
    }

}
