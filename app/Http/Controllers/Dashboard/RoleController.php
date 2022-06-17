<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index(){
        $roles = Role::query()->orderBy('id','DESC')->get();
        return view('dashboard.roles.index',compact('roles'));
    }

    public function create(){
        $permissions = Permission::query()->get()->chunk(4);
        return view('dashboard.roles.create',compact('permissions'));
    }

    public function store(Request $request)

    {

        $this->validate($request, [

            'name' => 'required|unique:roles,name',

            'permission' => 'required',

        ],[
           'name.required' => __('validation.required'),
           'name.unique' => __('validation.unique'),
           'permission.required' => __('validation.required'),
        ]);

        $role = Role::create(['name' => $request->input('name'),'guard_name' => 'admin']);

        $role->syncPermissions($request->input('permission'));

        toastr()->success(__('message.add_toastr'));
        return redirect()->route('admin.roles.index');

    }

    public function show($id)

    {

        $role = Role::find($id);

        $rolePermissions = Permission::join("role_has_permissions","role_has_permissions.permission_id","=","permissions.id")

            ->where("role_has_permissions.role_id",$id)

            ->get();

        return view('roles.show',compact('role','rolePermissions'));

    }

    public function edit($id)

    {

        $role = Role::find($id);

        $permissions = Permission::query()->get()->chunk(4);
//        $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id",$id)
//
//            ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')
//
//            ->all();
        $rolePermissions = $role->permissions()->get()
            ->pluck('id')
            ->all();
        //return $rolePermissions;
        return view('dashboard.roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }


    public function update(Request $request, $id)

    {

        $this->validate($request, [

            'name' => 'required',

            'permission' => 'required',

        ],[
            'name.required' => __('validation.required'),
            'name.unique' => __('validation.unique'),
            'permission.required' => __('validation.required'),
        ]);


        $role = Role::find($id);

        $role->name = $request->input('name');

        $role->save();

        $role->syncPermissions($request->input('permission'));

        toastr()->success(__('message.update_toastr'));
        return redirect()->route('admin.roles.index');

    }


    public function destroy(Request $request)

    {
        Role::query()->find($request->role_id)->delete();
        //DB::table("roles")->where('id',$id)->delete();
        toastr()->success(__('message.delete_toastr'));
        return redirect()->route('admin.roles.index');

    }
}
