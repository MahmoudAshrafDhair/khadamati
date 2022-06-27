<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{

    public function getProfile(){
        $user_id = Auth::guard('user-api')->id();
        $user = User::query()->find($user_id);
        return $this->sendResponse(new UserResource($user),"");
    }

    public function updateProfile(Request $request){
        $user_id = Auth::guard('user-api')->id();
        $user = User::query()->find($user_id);
        $data = $request->all();
        $validator = Validator::make($data,[
            'username' => 'required',
            'email' => 'required|email|unique:users,email,'.$user_id,
            'phone' => 'required|regex:/(05)[0-9]{8}/|unique:users,phone,'.$user_id,
            'city_id' => 'required|numeric',
        ],[
            'username.required' => __('apiValid.username_required'),
            'email.required' => __('apiValid.email_required'),
            'email.email' => __('apiValid.email_email'),
            'email.unique' => __('apiValid.email_unique'),
            'phone.required' => __('apiValid.phone_required'),
            'phone.unique' => __('apiValid.phone_unique'),
            'city_id.required' => __('apiValid.city_id_required'),
            'city_id.numeric' => __('apiValid.city_id_numeric'),
            'phone.regex' => __('apiValid.phone_regex'),
        ]);
        if($validator->fails()){
            return $this->sendError($validator->messages()->all(),410);
        }

        if ($request->hasFile('image')) {
            if($user->image != null){
                Storage::disk('public')->delete($user->image);
                $data['image'] = uploadImage($request->file('image'),'users');
            }else{
                $data['image'] = uploadImage($request->file('image'),'users');
            }
        }

        $user->update($data);

        return $this->sendResponse([],__('api.user_updated_success'));

    }

    public function updatePassword(Request $request){
        $user_id = Auth::guard('user-api')->id();
        $user = User::query()->find($user_id);
        $data = $request->all();
        $validator = Validator::make($data,[
            'old_password' => 'required',
            'password' => 'required|confirmed',
            'password_confirmation' => 'required'
        ],[
            'old_password.required' => __('apiValid.old_password_required'),
            'password.required' =>__('apiValid.password_required'),
            'password_confirmation.required' => __('apiValid.password_required'),
            'password.confirmed' => __('validation.confirmed'),
        ]);

        if($validator->fails()){
            return $this->sendError($validator->messages()->all(),410);
        }

        if (! Hash::check($request->old_password, $user->password)) {
            return $this->sendError(__('api.check_old_password'));
        }

        $user->update([
           'password' => Hash::make($request->password)
        ]);

        return $this->sendResponse([],__('api.update_password_success'));
    }
}
