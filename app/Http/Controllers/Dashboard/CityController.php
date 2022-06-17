<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\CityRequest;
use App\Models\City;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class CityController extends Controller
{
//    public function __construct()
//    {
////        $this->setting = Setting::query()->first();
//
//        $this->middleware('permission:city list', ['only' => ['index']]);
//
//        $this->middleware('permission:create city', ['only' => ['create','store']]);
//
//        $this->middleware('permission:edit city', ['only' => ['edit','update']]);
//
//        $this->middleware('permission:delete city', ['only' => ['destroy']]);
//
//    }

    public function index(){
        if(request()->ajax()){
            $cities = City::query()->latest()->get();
            return DataTables::make($cities)
                ->escapeColumns([])
                ->addIndexColumn()
                ->addColumn('name',function ($cities){
                    return $cities->getTranslation('name', app()->getLocale());
                })
                ->addColumn('actions',function ($cities){
                    return $cities->action_buttons;
                })
                ->rawColumns(['actions','name'])
                ->make();
        }
        return view('dashboard.cities.index');
    }

    public function create(){
        return view('dashboard.cities.create');
    }

    public function store(CityRequest $request){
        $data = $request->except('_token','name_ar','name_en');
        $data['name'] = ['ar' => $request->name_ar, 'en' => $request->name_en];
        City::query()->create($data);
        toastr()->success(__('message.add_toastr'));
        return redirect()->route('admin.cities.index');
    }

    public function edit($id){
        $city = City::query()->find($id);
        if (!$city){
            toastr()->error(__('message.error_toastr'));
            return redirect()->route('admin.cities.index');
        }
        return view('dashboard.cities.edit',compact('city'));
    }

    public function update(CityRequest $request, $id){
        $city = City::query()->find($id);
        $data = $request->except('_token','name_ar','name_en');
        if (!$city){
            toastr()->error(__('message.error_toastr'));
            return redirect()->route('admin.cities.index');
        }
        $data['name'] = ['ar' => $request->name_ar, 'en' => $request->name_en];
        $city->update($data);
        toastr()->success(__('message.update_toastr'));
        return redirect()->route('admin.cities.index');
    }

    public function destroy(Request $request){
        $city = City::query()->find($request->id);
        $city->delete();
        return response()->json(['success' => true]);
    }
}
