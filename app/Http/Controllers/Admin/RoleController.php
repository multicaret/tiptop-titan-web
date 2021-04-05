<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Str;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function __construct()
    {
        $this->middleware('permission:role.permissions.index', ['only' => ['index', 'store']]);
        $this->middleware('permission:role.permissions.create', ['only' => ['create', 'store']]);
        $this->middleware('permission:role.permissions.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:role.permissions.destroy', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|Response|View
     */
    public function index(Request $request)
    {
        $roles = Role::paginate(10);

        return view('admin.roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|Response|View
     */
    public function create()
    {
        $role = new Role();
        $permissions = config('defaults.all_permission.super');

        return view('admin.roles.form', compact('permissions', 'role'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return RedirectResponse
     */
    public function store(Request $request)
    {

        $this->validate($request, [
            'name' => 'required|unique:roles,name',
            'permissions' => 'required',
        ]);
        $permissions = array_keys($request->input('permissions', []));
        $roleName = Str::ucfirst($request->input('name'));
        $role = Role::create(['name' => $roleName]);
        $role->syncPermissions($permissions);

        return redirect()->route('admin.roles.index')
                         ->with('success', 'Role created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  Role  $role
     * @return Application|Factory|Response|View
     */
    public function show(Role $role)
    {
        $rolePermissions = Permission::join('role_has_permissions', 'role_has_permissions.permission_id', '=',
            'permissions.id')
                                     ->where('role_has_permissions.role_id', $role->id)
                                     ->get();

        return view('admin.roles.show', compact('role', 'rolePermissions'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Role  $role
     * @return Application|Factory|Response|View
     */
    public function edit(Role $role)
    {
        $permissions = config('defaults.all_permission.super');

//        $permissionsNames = array_values($role->permissions->pluck('name')->toArray());

        return view('admin.roles.form', compact('role', 'permissions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  Role  $role
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function update(Request $request, Role $role)
    {
        $this->validate($request, [
            'name' => 'required',
            'permissions' => 'required',
        ]);
        $permissions = array_keys($request->input('permissions', []));
        $role->name = Str::ucfirst($request->input('name'));
        $role->syncPermissions($permissions);
        $role->save();

        return redirect()->route('admin.roles.index')
                         ->with('success', 'Role updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Role  $role
     * @return RedirectResponse
     */
    public function destroy(Role $role)
    {
        $role->delete();

        return back()->with('message', [
            'type' => 'Success',
            'text' => 'Successfully Deleted',
        ]);
    }
}
