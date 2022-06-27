<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\CityResource;
use App\Http\Resources\HomeCategoryResource;
use App\Http\Resources\PagesResource;
use App\Http\Resources\SliderResource;
use App\Http\Resources\SubCategoryResource;
use App\Models\Category;
use App\Models\City;
use App\Models\ContactUS;
use App\Models\Setting;
use App\Models\Slider;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GeneralController extends Controller
{

    public function getCities(){
        $cities = City::query()->latest()->get();
        return $this->sendResponse(CityResource::collection($cities),"");
    }

    public function getServices(){
        $services = SubCategory::query()->latest()->get();
        return $this->sendResponse(SubCategoryResource::collection($services),"");
    }

    public function getPages(){
        $setting = Setting::query()->first();
        return $this->sendResponse(new PagesResource($setting),"");
    }

    public function contactUs(Request $request){
        $data = $request->all();
        $validator = Validator::make($data,[
            'name' => 'required',
            'email' => 'required|email',
            'message' => 'required',
            'type' => 'required',
        ],[
            'name.required' => __('apiValid.username_required'),
            'email.required' => __('apiValid.email_required'),
            'email.email' => __('apiValid.email_email'),
            'message.required' => __('apiValid.message_required'),
            'type.required' => __('apiValid.type_user_required'),
        ]);

        if($validator->fails()){
            return $this->sendError($validator->messages()->all(),410);
        }

        ContactUS::query()->create($data);
        return $this->sendResponse([],__('api.contact_success'));
    }

    public function home(){
        $sliders = Slider::query()->latest()->get();
        $categories = Category::query()->latest()->limit(10)->get();
        $subcategory = SubCategory::query()->orderBy('order_total','desc')->limit(10)->get();
        return $this->sendResponse([
            'sliders' => SliderResource::collection($sliders),
            'categories' => HomeCategoryResource::collection($categories),
            'subCategories' => SubCategoryResource::collection($subcategory)
        ],"");
    }
}
