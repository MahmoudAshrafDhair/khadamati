<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\DayRequest;
use App\Models\Day;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class DayController extends Controller
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
            $days = Day::query()->latest()->get();
            return DataTables::make($days)
                ->escapeColumns([])
                ->addIndexColumn()
                ->addColumn('name',function ($day){
                    return $day->getTranslation('name', app()->getLocale());
                })
                ->addColumn('actions',function ($day){
                    return $day->action_buttons;
                })
                ->rawColumns(['actions','name'])
                ->make();
        }
        return view('dashboard.days.index');
    }

    public function create(){
        return view('dashboard.days.create');
    }

    public function store(DayRequest $request){
        $data = $request->except('_token','name_ar','name_en');
        $data['name'] = ['ar' => $request->name_ar, 'en' => $request->name_en];
        Day::query()->create($data);
        toastr()->success(__('message.add_toastr'));
        return redirect()->route('admin.days.index');
    }

    public function edit($id){
        $day = Day::query()->find($id);
        if (!$day){
            toastr()->error(__('message.error_toastr'));
            return redirect()->route('admin.cities.index');
        }
        return view('dashboard.days.edit',compact('day'));
    }

    public function update(DayRequest $request, $id){
        $day = Day::query()->find($id);
        $data = $request->except('_token','name_ar','name_en');
        if (!$day){
            toastr()->error(__('message.error_toastr'));
            return redirect()->route('admin.cities.index');
        }
        $data['name'] = ['ar' => $request->name_ar, 'en' => $request->name_en];
        $day->update($data);
        toastr()->success(__('message.update_toastr'));
        return redirect()->route('admin.days.index');
    }

    public function destroy(Request $request){
        $day = Day::query()->find($request->id);
        $day->delete();
        return response()->json(['success' => true]);
    }
}
