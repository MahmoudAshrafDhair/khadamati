<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\WorkerRequest;
use App\Models\City;
use App\Models\SubCategory;
use App\Models\Worker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;

class WorkerController extends Controller
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
            $workers = Worker::query()->latest()->get();
            return DataTables::make($workers)
                ->escapeColumns([])
                ->addIndexColumn()
                ->addColumn('image',function ($worker){
                    if ($worker->image == null) {
                        return '<img src="'. asset('assets/image/guest-user.jpg').'" class="img-thumbnail" alt="..." width="70" height="40">';
                    }else{
                        return '<img src="'. asset('storage/'.$worker->image).'" class="img-thumbnail" alt="..." width="70" height="40">';
                    }

                })
                ->addColumn('service',function ($worker){
                    return $worker->subCategory->getTranslation('name', app()->getLocale());
                })
                ->addColumn('actions',function ($worker){
                    return $worker->action_buttons;
                })
                ->rawColumns(['actions','image','service'])
                ->make();
        }
        return view('dashboard.workers.index');
    }

    public function create(){
        $cities = City::query()->latest()->get();
        $subCategories = SubCategory::query()->latest()->get();
        return view('dashboard.workers.create',compact('cities','subCategories'));
    }

    public function store(WorkerRequest $request){
        $data = $request->except('_token','image','password');
        if ($request->hasFile('image')) {
            $data['image'] = uploadImage($request->file('image'),'workers');
        }
        $data['password'] = Hash::make($request->password);
        $data['code'] = 1;
        $data['active'] = 1;
        Worker::query()->create($data);
        toastr()->success(__('message.add_toastr'));
        return redirect()->route('admin.workers.index');
    }

    public function edit($id){
        $cities = City::query()->latest()->get();
        $subCategories = SubCategory::query()->latest()->get();
        $worker = Worker::query()->find($id);
        if (!$worker){
            toastr()->error(__('message.error_toastr'));
            return redirect()->route('admin.sub.categories.index');
        }
        return view('dashboard.workers.edit',compact('worker','cities','subCategories'));
    }

    public function update(WorkerRequest $request, $id){
        $worker = Worker::query()->find($id);
        $data = $request->except('_token','image','password');
        if (!$worker){
            toastr()->error(__('message.error_toastr'));
            return redirect()->route('admin.sub.categories.index');
        }
        if ($request->hasFile('image')){
            if($worker->image != null){
                Storage::disk('public')->delete($worker->image);
                $data['image'] = uploadImage($request->file('image'),'workers');
            }else{
                $data['image'] = uploadImage($request->file('image'),'workers');
            }
        }
        $data['password'] = $request->has('password') && $request->password != null ? Hash::make($request->password) : $worker->password;
        $worker->update($data);
        toastr()->success(__('message.update_toastr'));
        return redirect()->route('admin.users.index');
    }

    public function destroy(Request $request){
        $worker = Worker::query()->find($request->id);
        $worker->delete();
        return response()->json(['success' => true]);
    }
}
