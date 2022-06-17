<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubCategoryRequest;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;

class SubCategoryController extends Controller
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
            $subCategories = SubCategory::query()->latest()->get();
            return DataTables::make($subCategories)
                ->escapeColumns([])
                ->addIndexColumn()
                ->addColumn('image',function ($subCategory){
                    return '<img src="'.asset('storage/'.$subCategory->image).'" class="img-thumbnail" alt="..." width="70" height="40">';
                })
                ->addColumn('name',function ($subCategory){
                    return $subCategory->getTranslation('name', app()->getLocale());
                })
                ->addColumn('category',function ($subCategory){
                    return $subCategory->category->getTranslation('name', app()->getLocale());
                })
                ->addColumn('actions',function ($category){
                    return $category->action_buttons;
                })
                ->rawColumns(['actions','name','image','category'])
                ->make();
        }
        return view('dashboard.sub_categories.index');
    }

    public function create(){
        $categories = Category::query()->latest()->get();
        return view('dashboard.sub_categories.create',compact('categories'));
    }

    public function store(SubCategoryRequest $request){
        $data = $request->except('_token','name_ar','name_en','image');
        $data['name'] = ['ar' => $request->name_ar, 'en' => $request->name_en];
        if ($request->hasFile('image')) {
            $data['image'] = uploadImage($request->file('image'),'sub_categories');
        }
        SubCategory::query()->create($data);
        toastr()->success(__('message.add_toastr'));
        return redirect()->route('admin.sub.categories.index');
    }

    public function edit($id){
        $categories = Category::query()->latest()->get();
        $subCategory = SubCategory::query()->find($id);
        if (!$subCategory){
            toastr()->error(__('message.error_toastr'));
            return redirect()->route('admin.sub.categories.index');
        }
        return view('dashboard.sub_categories.edit',compact('subCategory','categories'));
    }

    public function update(SubCategoryRequest $request, $id){
        $subCategory = SubCategory::query()->find($id);
        $data = $request->except('_token','name_ar','name_en','image');
        if (!$subCategory){
            toastr()->error(__('message.error_toastr'));
            return redirect()->route('admin.sub.categories.index');
        }
        $data['name'] = ['ar' => $request->name_ar, 'en' => $request->name_en];
        if ($request->hasFile('image')){
            if($subCategory->image != null){
                Storage::disk('public')->delete($subCategory->image);
                $data['image'] = uploadImage($request->file('image'),'sub_categories');
            }else{
                $data['image'] = uploadImage($request->file('image'),'sub_categories');
            }
        }
        $subCategory->update($data);
        toastr()->success(__('message.update_toastr'));
        return redirect()->route('admin.sub.categories.index');
    }

    public function destroy(Request $request){
        $category = SubCategory::query()->find($request->id);
        $category->delete();
        return response()->json(['success' => true]);
    }
}
