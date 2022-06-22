<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Mail\VerificationEmail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    public function register(Request $request){
        $data = $request->all();
        $validator = Validator::make($data,[
            'username' => 'required',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|unique:users,phone|regex:/(05)[0-9]{8}/',
            'password' => 'required',
            'city_id' => 'required|numeric',
        ],[
            'username.required' => __('apiValid.username_required'),
            'email.required' => __('apiValid.email_required'),
            'email.email' => __('apiValid.email_email'),
            'email.unique' => __('apiValid.email_unique'),
            'phone.required' => __('apiValid.phone_required'),
            'password.required' => __('apiValid.password_required'),
            'phone.unique' => __('apiValid.phone_unique'),
            'city_id.required' => __('apiValid.city_id_required'),
            'city_id.numeric' => __('apiValid.city_id_numeric'),
            'phone.regex' => __('apiValid.phone_regex'),
        ]);

        if($validator->fails()){
            return $this->sendError($validator->messages()->all(),410);
        }
        $data['active'] = 0;
        $data['password'] = Hash::make($request->password);
        $data['code'] = rand(1000, 9999);
        $user = User::query()->create($data);
        Mail::to($user->email)->send(new VerificationEmail(['name' => $user->username, 'code' => $user->code]));
        return $this->sendResponse(new UserResource($user),__('api.user_register_success'));
    }

    public function check_code(Request $request){
        $data = $request->all();
        $validator = Validator::make($data,[
           'user_id' => 'required|exists:users,id',
            'code' => 'required|numeric',
            'fcm' => 'required'
        ],[
            'user_id.required' => __('apiValid.user_id_required'),
            'user_id.exists' => __('apiValid.user_id_exists'),
            'code.required' => __('apiValid.code_required'),
            'code.numeric' => __('apiValid.code_numeric'),
            'fcm.required' => __('apiValid.fcm_required'),
        ]);

        if($validator->fails()){
            return $this->sendError($validator->messages()->all(),410);
        }

        $user = User::query()->find($request->user_id);

        if($user->code == $request->code){
            $user->update(['active' => 1 , 'fcm' => $request->fcm]);
            $token = $user->createToken('web')->plainTextToken;
            $response = collect([
                'id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
                'phone' => $user->phone,
                'active' => $user->active,
                'city' => $user->city->name,
                'token' => $token,
            ]);
            return $this->sendResponse($response,__('api.user_login_success'));
        }else{
            return $this->sendError(__('api.user_code_fail'));
        }
    }

    public function login(Request $request){
        $data = $request->all();
        $validator = Validator::make($data,[
            'phone' => 'required|regex:/(05)[0-9]{8}/',
            'password' => 'required',
            'fcm' => 'required'
        ],[
            'phone.required' => __('apiValid.phone_required'),
            'password.required' => __('apiValid.password_required'),
            'phone.unique' => __('apiValid.phone_unique'),
            'phone.regex' => __('apiValid.phone_regex'),
            'fcm.required' => __('apiValid.fcm_required'),
        ]);

        if($validator->fails()){
            return $this->sendError($validator->messages()->all(),410);
        }

        $user = User::query()->where('phone', $request->phone)->first();
        if (! $user || ! Hash::check($request->password, $user->password)) {
            return $this->sendError(__('api.user_login_fail'));
        }

        if($user->active === 0){
            return $this->sendError(__('api.check_code'));
        }

        $user->update(['fcm' => $request->fcm]);

        $token = $user->createToken('web')->plainTextToken;
        $response = collect([
            'id' => $user->id,
            'username' => $user->username,
            'email' => $user->email,
            'phone' => $user->phone,
            'active' => $user->active,
            'city' => $user->city->name,
            'token' => $token,
        ]);
        return $this->sendResponse($response,__('api.user_login_success'));
    }

    public function logout(Request $request){
        Auth::guard('user-api')->user()->currentAccessToken()->delete();
        return $this->sendResponse([],__('api.logout_success'));
    }


}
