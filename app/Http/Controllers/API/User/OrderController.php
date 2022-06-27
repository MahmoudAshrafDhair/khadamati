<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\HomeCategoryResource;
use App\Http\Resources\OrderResource;
use App\Http\Resources\WorkerResource;
use App\Models\Category;
use App\Models\Order;
use App\Models\Rate;
use App\Models\SubCategory;
use App\Models\Worker;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{

    public function getSubCategoriesByCategoriesId(Request $request){
        $data = $request->all();
        $validator = Validator::make($data,[
            'category_id' => 'required'
        ],[
          'category_id.required' => __('apiValid.category_id_required'),
        ]);

        if ($validator->fails()){
            return $this->sendError($validator->messages()->all(),410);
        }

        $category = Category::query()->with(['subCategories'])->first();
        return $this->sendResponse(new CategoryResource($category),"");
    }

    public function getCategories(){
        $categories = Category::query()->latest()->paginate($this->perPage);
        return $this->sendResponse([
           'categories' => HomeCategoryResource::collection($categories),
            'paginate' => paginate($categories)
        ],"");
    }

    public function getWorkers(){
        $city_id = Auth::guard('user-api')->user()->city_id;
        $workers = Worker::query()->where('city_id',$city_id)->paginate($this->perPage);
        return $this->sendResponse([
            'worker' =>WorkerResource::collection($workers),
            'paginate' => paginate($workers)
        ],"");
    }

    public function storeOrder(Request $request){
        $validator = Validator::make($request->all(),[
            'subCategory_id' => 'required',
            'worker_id' => 'required',
            'time_type' => 'required',
            'longitude' => 'required',
            'latitude' => 'required',
            'description' => 'required',
            'date' => 'required_if:time_type,2',
        ],[
            'subCategory_id.required' => __('apiValid.subCategory_id_required'),
            'worker_id.required' => __('apiValid.user_id_required'),
            'time_type.required' => __('apiValid.user_id_required'),
            'longitude.required' => __('apiValid.longitude_required'),
            'latitude.required' => __('apiValid.latitude_required'),
            'description.required' => __('apiValid.description_required'),
            'date.required_if' => __('apiValid.date_required'),
        ]);

        if ($validator->fails()){
            return $this->sendError($validator->messages()->all(),410);
        }
        $data = $request->all();
        $user_id = Auth::guard('user-api')->id();
        if($request->time_type == 2){
            $data['data'] = $request->data;
        }

        if ($request->hasFile('image')) {
            $data['image'] = uploadImage($request->file('image'),'orders');
        }
        $data['user_id'] = $user_id;
        $data['type'] = 1;
        $data['is_completed'] = 0;
        $order = Order::query()->create($data);
        $subCategory = SubCategory::query()->find($request->subCategory_id);
        $subCategory->update([
            'order_total' => $subCategory->order_total += 1
        ]);
        return $this->sendResponse(new OrderResource($order),"");
    }

    public function getOrders(Request $request){
        $data = $request->all();
        $validator = Validator::make($data,[
            'type' => 'required'
        ],[
           'type.required' => __('apiValid.type_required')
        ]);

        if ($validator->fails()){
            return $this->sendError($validator->messages()->all(),410);
        }
        $user_id = Auth::guard('user-api')->id();
        $orders = Order::query()->where('user_id',$user_id);

        if($request->type == 0){
            $orders = $orders->paginate($this->perPage);
        }elseif ($request->type == 4) {
            $orders = $orders->where('type',4)->orWhere('is_completed',1)->paginate($this->perPage);
        }else{
            $orders = $orders->where('type',$request->type)->paginate($this->perPage);
        }
        $pending_order_count = Order::query()->where('user_id',$user_id)->where('type',1)->count();
        $current_order_count = Order::query()->where('user_id',$user_id)->where('type',2)->count();
        $rejected_order_count = Order::query()->where('user_id',$user_id)->where('type',3)->count();
        $completed_order_count = Order::query()->where('user_id',$user_id)->where('type',4)->count();

        $order_count = collect([
            'pending_order_count' => $pending_order_count,
            'current_order_count' => $current_order_count,
            'rejected_order_count' => $rejected_order_count,
            'completed_order_count' => $completed_order_count,
        ]);

        return $this->sendResponse([
            'order_count' => $order_count,
            'orders' => OrderResource::collection($orders),
            'paginate' => paginate($orders)
        ],"");

    }

    public function ChangeTypeToCompleted(Request $request){
        $data = $request->all();
        $validator = Validator::make($data,[
           'order_id' => 'required'
        ],[
            'order_id.required' => __('apiValid.type_required')
        ]);

        if ($validator->fails()){
            return $this->sendError($validator->messages()->all(),410);
        }

        $order = Order::query()->find($request->order_id);
        $order->update([
            'type' => 4
        ]);

        return $this->sendResponse(new OrderResource($order),__('api.order_updated_success'));
    }

    public function storeRating(Request $request){
        $data = $request->all();
        $validator = Validator::make($data,[
            'order_id' => 'required',
            'rating' => 'required',
        ],[
            'order_id.required' => __('apiValid.type_required'),
            'rating.required' => __('apiValid.rating_required'),
        ]);

        if ($validator->fails()){
            return $this->sendError($validator->messages()->all(),410);
        }

        $order = Order::query()->find($request->order_id);
        $data['user_id'] = $order->user_id;
        $data['worker_id'] = $order->worker_id;
        if($request->has('description')){
            $data['description'] = $request->description;
        }
        Rate::query()->create($data);
        return $this->sendResponse([],__('api.rating_success'));
    }
}
