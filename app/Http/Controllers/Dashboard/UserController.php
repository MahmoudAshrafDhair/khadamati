<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\City;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{
    //    private $setting;

    public function __construct()
    {
//        $this->setting = Setting::query()->first();
//
//        $this->middleware('permission:article list', ['only' => ['index']]);
//
//        $this->middleware('permission:create article', ['only' => ['create','store']]);
//
//        $this->middleware('permission:edit article', ['only' => ['edit','update']]);
//
//        $this->middleware('permission:delete article', ['only' => ['destroy']]);

    }

    public function index(){
        if(request()->ajax()){
            $users = User::query()->latest()->get();
            return DataTables::make($users)
                ->escapeColumns([])
                ->addIndexColumn()
                ->addColumn('image',function ($user){
                    return '<img src="'.asset('storage/'.$user->image).'" class="img-thumbnail" alt="..." width="70" height="40">';
                })
                ->addColumn('actions',function ($category){
                    return $category->action_buttons;
                })
                ->rawColumns(['actions','image'])
                ->make();
        }
        return view('dashboard.users.index');
    }

    public function create(){
        $cities = City::query()->latest()->get();
        return view('dashboard.users.create',compact('cities'));
    }

    public function store(UserRequest $request){
        $data = $request->except('_token','image','password');
        if ($request->hasFile('image')) {
            $data['image'] = uploadImage($request->file('image'),'users');
        }
        $data['password'] = Hash::make($request->password);
        $data['code'] = 1;
        User::query()->create($data);
        toastr()->success(__('message.add_toastr'));
        return redirect()->route('admin.users.index');
    }

    public function edit($id){
        $cities = City::query()->latest()->get();
        $user = User::query()->find($id);
        if (!$user){
            toastr()->error(__('message.error_toastr'));
            return redirect()->route('admin.sub.categories.index');
        }
        return view('dashboard.users.edit',compact('user','cities'));
    }

    public function update(UserRequest $request, $id){
        $user = User::query()->find($id);
        $data = $request->except('_token','image','password');
        if (!$user){
            toastr()->error(__('message.error_toastr'));
            return redirect()->route('admin.sub.categories.index');
        }
        if ($request->hasFile('image')){
            if($user->image != null){
                Storage::disk('public')->delete($user->image);
                $data['image'] = uploadImage($request->file('image'),'users');
            }else{
                $data['image'] = uploadImage($request->file('image'),'users');
            }
        }
        $data['password'] = $request->has('password') && $request->password != null ? Hash::make($request->password) : $user->password;
        $user->update($data);
        toastr()->success(__('message.update_toastr'));
        return redirect()->route('admin.users.index');
    }

    public function destroy(Request $request){
        $user = User::query()->find($request->id);
        $user->delete();
        return response()->json(['success' => true]);
    }
}
