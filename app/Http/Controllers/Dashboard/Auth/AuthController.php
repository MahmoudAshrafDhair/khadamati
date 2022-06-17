<?php

namespace App\Http\Controllers\Dashboard\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminLoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showForm(){
        return view('dashboard.auth.login');
    }

    public function login(AdminLoginRequest $request){
        $remmber = $request->has('remember');
        if(Auth::guard('admin')->attempt(['email'=>$request->email,'password' => $request->password],$remmber)){
            toastr()->success(__('validation.login_successfully'));
            return redirect()->route('admin.dashboard');
        }else{
            toastr()->error(__('validation.login'));
            return redirect()->route('admin.showForm');
        }
    }

    public function logout(){
        Auth::guard('admin')->logout();
        toastr()->success(__('message.logout'));
        return redirect()->route('admin.showForm');
    }
}
