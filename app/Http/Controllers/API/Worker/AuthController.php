<?php

namespace App\Http\Controllers\API\Worker;

use App\Http\Controllers\Controller;
use App\Http\Resources\WorkerResource;
use App\Mail\VerificationEmail;
use App\Models\Worker;
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
            'email' => 'required|email|unique:workers,email',
            'phone' => 'required|unique:workers,phone|regex:/(05)[0-9]{8}/',
            'password' => 'required',
            'city_id' => 'required|numeric',
            'subCategory_id' => 'required|numeric',
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
            'subCategory_id.required' => __('apiValid.subCategory_id_required'),
            'subCategory_id.numeric' => __('apiValid.subCategory_id_numeric'),
            'phone.regex' => __('apiValid.phone_regex'),
        ]);

        if($validator->fails()){
            return $this->sendError($validator->messages()->all(),410);
        }
        $data['active'] = 0;
        $data['password'] = Hash::make($request->password);
        $data['code'] = rand(1000, 9999);
        $worker = Worker::query()->create($data);
        Mail::to($worker->email)->send(new VerificationEmail(['name' => $worker->username, 'code' => $worker->code]));
        return $this->sendResponse(new WorkerResource($worker),__('api.worker_register_success'));
    }

    public function check_code(Request $request){
        $data = $request->all();
        $validator = Validator::make($data,[
            'worker_id' => 'required|exists:workers,id',
            'code' => 'required|numeric',
            'fcm' => 'required'
        ],[
            'worker_id.required' => __('apiValid.user_id_required'),
            'worker_id.exists' => __('apiValid.user_id_exists'),
            'code.required' => __('apiValid.code_required'),
            'code.numeric' => __('apiValid.code_numeric'),
            'fcm.required' => __('apiValid.fcm_required'),
        ]);

        if($validator->fails()){
            return $this->sendError($validator->messages()->all(),410);
        }

        $worker = Worker::query()->find($request->worker_id);

        if($worker->code == $request->code){
            $worker->update(['active' => 1 , 'fcm' => $request->fcm]);
            $token = $worker->createToken('web')->plainTextToken;
            $response = collect([
                'id' => $worker->id,
                'username' => $worker->username,
                'email' => $worker->email,
                'phone' => $worker->phone,
                'active' => $worker->active,
                'city' => $worker->city->name,
                'service' => $worker->subCategory->name,
                'token' => $token,
            ]);
            return $this->sendResponse($response,__('api.worker_login_success'));
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

        $worker = Worker::query()->where('phone', $request->phone)->first();
        if (! $worker || ! Hash::check($request->password, $worker->password)) {
            return $this->sendError(__('api.user_login_fail'));
        }

        if($worker->active === 0){
            return $this->sendError(__('api.check_code'));
        }

        $worker->update(['fcm' => $request->fcm]);

        $token = $worker->createToken('web')->plainTextToken;
        $response = collect([
            'id' => $worker->id,
            'username' => $worker->username,
            'email' => $worker->email,
            'phone' => $worker->phone,
            'active' => $worker->active,
            'city' => $worker->city->name,
            'service' => $worker->subCategory->name,
            'token' => $token,
        ]);
        return $this->sendResponse($response,__('api.worker_login_success'));
    }

    public function logout(Request $request){
        Auth::guard('worker-api')->user()->currentAccessToken()->delete();
        return $this->sendResponse([],__('api.logout_success'));
    }
}
