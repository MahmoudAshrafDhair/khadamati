<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\ContactUS;
use App\Models\Order;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ContactController extends Controller
{
    public function index_user(){
        if(request()->ajax()){
            $contacts = ContactUS::query()->where('type',1)->latest()->get();
            return DataTables::make($contacts)
                ->escapeColumns([])
                ->addIndexColumn()
                ->addColumn('actions',function ($category){
                    return $category->action_buttons_user;
                })
                ->rawColumns(['actions'])
                ->make();
        }
        return view('dashboard.contacts.index_user');
    }

    public function show_user($id){
        $contact = ContactUS::query()->find($id);
        if (!$contact){
            toastr()->error(__('message.error_toastr'));
            return redirect()->route('admin.orders.index');
        }
        return view('dashboard.contacts.show_user',compact('contact'));
    }

    public function destroy_user(Request $request){
        $contact = ContactUS::query()->find($request->id);
        $contact->delete();
        return response()->json(['success' => true]);
    }

    public function index_worker(){
        if(request()->ajax()){
            $contacts = ContactUS::query()->where('type',2)->latest()->get();
            return DataTables::make($contacts)
                ->escapeColumns([])
                ->addIndexColumn()
                ->addColumn('actions',function ($category){
                    return $category->action_buttons_user;
                })
                ->rawColumns(['actions'])
                ->make();
        }
        return view('dashboard.contacts.index_worker');
    }

    public function show_worker($id){
        $contact = ContactUS::query()->find($id);
        if (!$contact){
            toastr()->error(__('message.error_toastr'));
            return redirect()->route('admin.orders.index');
        }
        return view('dashboard.contacts.show_user',compact('contact'));
    }

    public function destroy_worker(Request $request){
        $contact = ContactUS::query()->find($request->id);
        $contact->delete();
        return response()->json(['success' => true]);
    }
}
