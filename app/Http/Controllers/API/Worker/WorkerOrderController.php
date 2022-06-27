<?php

namespace App\Http\Controllers\API\Worker;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class WorkerOrderController extends Controller
{

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

        $worker_id = Auth::guard('worker-api')->user()->id;
        $orders = Order::query()->where('worker_id',$worker_id);
        if($request->type == 0){
            $orders = $orders->paginate($this->perPage);
        }elseif ($request->type == 4) {
            $orders = $orders->where('type',4)->orWhere('is_completed',1)->paginate($this->perPage);
        }else{
            $orders = $orders->where('type',$request->type)->paginate($this->perPage);
        }

        $pending_order_count = Order::query()->where('worker_id',$worker_id)->where('type',1)->count();
        $current_order_count = Order::query()->where('worker_id',$worker_id)->where('type',2)->count();
        $rejected_order_count = Order::query()->where('worker_id',$worker_id)->where('type',3)->count();
        $completed_order_count = Order::query()->where('worker_id',$worker_id)->where('type',4)->count();

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

    public function acceptOrder(Request $request){
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
            'type' => 2
        ]);

        return $this->sendResponse(new OrderResource($order),__('api.order_updated_success'));
    }

    public function rejectedOrder(Request $request){
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
            'type' => 3
        ]);

        return $this->sendResponse(new OrderResource($order),__('api.order_updated_success'));
    }

    public function completedOrder(Request $request){
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
            'is_completed' => 1
        ]);

        return $this->sendResponse(new OrderResource($order),__('api.order_updated_success'));
    }
}
