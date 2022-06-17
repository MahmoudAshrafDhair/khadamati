<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;

class CategoryController extends Controller
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
            $categories = Category::query()->latest()->get();
            return DataTables::make($categories)
                ->escapeColumns([])
                ->addIndexColumn()
                ->addColumn('image',function ($category){
                    return '<img src="'.asset('storage/'.$category->image).'" class="img-thumbnail" alt="..." width="70" height="40">';
                })
                ->addColumn('name',function ($category){
                    return $category->getTranslation('name', app()->getLocale());
                })
                ->addColumn('actions',function ($category){
                    return $category->action_buttons;
                })
                ->rawColumns(['actions','name','image'])
                ->make();
        }
        return view('dashboard.categories.index');
    }

    public function create(){
        return view('dashboard.categories.create');
    }

    public function store(CategoryRequest $request){
        $data = $request->except('_token','name_ar','name_en','image');
        $data['name'] = ['ar' => $request->name_ar, 'en' => $request->name_en];
        if ($request->hasFile('image')) {
            $data['image'] = uploadImage($request->file('image'),'categories');
        }
        Category::query()->create($data);
        toastr()->success(__('message.add_toastr'));
        return redirect()->route('admin.categories.index');
    }

    public function edit($id){
        $category = Category::query()->find($id);
        if (!$category){
            toastr()->error(__('message.error_toastr'));
            return redirect()->route('admin.articles.index');
        }
        return view('dashboard.categories.edit',compact('category'));
    }

    public function update(CategoryRequest $request, $id){
        $category = Category::query()->find($id);
        $data = $request->except('_token','name_ar','name_en','image');
        if (!$category){
            toastr()->error(__('message.error_toastr'));
            return redirect()->route('admin.categories.index');
        }
        $data['name'] = ['ar' => $request->name_ar, 'en' => $request->name_en];
        if ($request->hasFile('image')){
            if($category->image != null){
                Storage::disk('public')->delete($category->image);
                $data['image'] = uploadImage($request->file('image'),'categories');
            }else{
                $data['image'] = uploadImage($request->file('image'),'categories');
            }
        }
        $category->update($data);
        toastr()->success(__('message.update_toastr'));
        return redirect()->route('admin.categories.index');
    }

    public function destroy(Request $request){
        $category = Category::query()->find($request->id);
        $category->delete();
        return response()->json(['success' => true]);
    }
}
