<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\SliderRequest;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;

class SliderController extends Controller
{
   // private $setting;

//    public function __construct()
//    {
//        $this->setting = Setting::query()->first();
//
//        $this->middleware('permission:slider list', ['only' => ['index']]);
//
//        $this->middleware('permission:create slider', ['only' => ['create','store']]);
//
//        $this->middleware('permission:edit slider', ['only' => ['edit','update']]);
//
//        $this->middleware('permission:delete slider', ['only' => ['destroy']]);
//
//    }

    public function index(){
        if(request()->ajax()){
            $sliders = Slider::query()->latest()->get();
            return DataTables::make($sliders)
                ->escapeColumns([])
                ->addIndexColumn()
                ->addColumn('image',function ($slider){
                    return '<img src="'.asset('storage/'.$slider->image).'" class="img-thumbnail" alt="..." width="70" height="40">';
                })
                ->addColumn('actions',function ($articles){
                    return $articles->action_buttons;
                })
                ->rawColumns(['actions','image'])
                ->make();
        }
        return view('dashboard.sliders.index');
    }

    public function create(){
        return view('dashboard.sliders.create');
    }

    public function store(SliderRequest $request){
        $data = $request->except('_token','image');
        if ($request->hasFile('image')) {
            $data['image'] = uploadImage($request->file('image'),'sliders');
        }
        Slider::query()->create($data);
        toastr()->success(__('message.add_toastr'));
        return redirect()->route('admin.sliders.index');
    }

    public function edit($id){
        $slider = Slider::query()->find($id);
        if (!$slider){
            toastr()->error(__('message.error_toastr'));
            return redirect()->route('admin.sliders.index');
        }
        return view('dashboard.sliders.edit',compact('slider'));
    }

    public function update(SliderRequest $request, $id){
        $slider = Slider::query()->find($id);
        $data = $request->except('_token','image');
        if (!$slider){
            toastr()->error(__('message.error_toastr'));
            return redirect()->route('admin.sliders.index');
        }
        if ($request->hasFile('image')){
            if($slider->image != null){
                Storage::disk('public')->delete($slider->image);
                $data['image'] = uploadImage($request->file('image'),'sliders');
            }else{
                $data['image'] = uploadImage($request->file('image'),'sliders');
            }
        }
        $slider->update($data);
        toastr()->success(__('message.update_toastr'));
        return redirect()->route('admin.sliders.index');
    }

    public function destroy(Request $request){
        $slider = Slider::query()->find($request->id);
        $slider->delete();
        return response()->json(['success' => true]);
    }
}
