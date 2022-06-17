<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminRequest;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\DataTables;

class AdminController extends Controller
{
    public function index(){
        if (request()->ajax()) {
            $admins = Admin::query()->get();
            return DataTables::make($admins)
                ->escapeColumns([])
                ->addColumn('actions',function ($admins){
                    return $admins->action_buttons;
                })
                ->addColumn('roles', function ($admins) {
                    $adminRole = $admins->roles->pluck('name', 'name')->all();
                    $roles = '';
                    foreach ($adminRole as $role) {
                        $roles .= '&nbsp;<span class="label label-light-danger label-inline font-weight-bold">' . $role . '</span>';
                    }
                    return $roles;
                })
                ->rawColumns(['actions','roles'])
                ->make();
        }
        return view('dashboard.admins.index');
    }

    public function create(){
        $roles = Role::query()->pluck('name', 'name')->all();
        return view('dashboard.admins.create',compact('roles'));
    }

    public function store(AdminRequest $request){
        $data = $request->except('_token', 'roles_name','image');
        $data['password'] = Hash::make($request->password);
        if ($request->hasFile('image')) {
           $data['image'] = uploadImage($request->file('image'),'admins');
        }
        $admin = Admin::query()->create($data);
        $admin->assignRole($request->input('roles_name'));
        toastr()->success(__('message.add_toastr'));
        return redirect()->route('admin.index');
    }

    public function edit($id){
        $admin = Admin::query()->find($id);
        $roles = Role::query()->pluck('name', 'name')->all();
        $adminRole = $admin->roles->pluck('name', 'name')->all();
        return view('dashboard.admins.edit', compact('admin', 'roles', 'adminRole'));
    }

    public function update(AdminRequest $request,$id){
        $admin = Admin::query()->find($id);
        $data = $request->except('_token', 'roles_name','image');
        if ($request->hasFile('image')){
            if($admin->image != null){
                Storage::disk('public')->delete($admin->image);
                $data['image'] = uploadImage($request->file('image'),'admins');
            }else{
                $data['image'] = uploadImage($request->file('image'),'admins');
            }
        }
        $data['password'] = $request->has('password') && $request->password != null ? Hash::make($request->password) : $admin->password;
        $admin->update($data);
        DB::table('model_has_roles')->where('model_id', $id)->delete();
        $admin->assignRole($request->input('roles_name'));
        toastr()->success(__('message.update_toastr'));
        return redirect()->route('admin.index');
    }

    public function destroy(Request $request){
        $admin = Admin::query()->find($request->id);
        $admin->delete();
        DB::table('model_has_roles')->where('model_id', $request->id)->delete();
        return response()->json(['success' => true]);
    }
}
