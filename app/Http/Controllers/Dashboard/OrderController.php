<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class OrderController extends Controller
{
    public function index(){
        if(request()->ajax()){
            $orders = Order::query()->latest()->get();
            return DataTables::make($orders)
                ->escapeColumns([])
                ->addIndexColumn()
                ->addColumn('states',function ($order){
                    return $order->states ;
                })
                ->addColumn('subCategory',function ($order){
                    return $order->subCategories->getTranslation('name', app()->getLocale());
                })
                ->addColumn('user',function ($order){
                    return $order->user->username;
                })
                ->addColumn('worker',function ($order){
                    return $order->worker->username;
                })
                ->addColumn('actions',function ($category){
                    return $category->action_buttons;
                })
                ->rawColumns(['actions','subCategory','states','user','worker'])
                ->make();
        }
        return view('dashboard.orders.index');
    }

    public function show($id){
        $order = Order::query()->with(['subCategories','user','worker'])->find($id);
        if (!$order){
            toastr()->error(__('message.error_toastr'));
            return redirect()->route('admin.orders.index');
        }
        return view('dashboard.orders.show',compact('order'));
    }

    public function destroy(Request $request){
        $order = Order::query()->find($request->id);
        $order->delete();
        return response()->json(['success' => true]);
    }
}
