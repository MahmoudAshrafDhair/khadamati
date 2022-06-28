<?php

namespace App\Http\Controllers\API\Worker;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Mail\ForgetPasswordMail;
use App\Models\Worker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class WorkerForgetPasswordController extends Controller
{
    public function checkEmail(Request $request) {
        $data = $request->all();
        $validator = Validator::make($data,[
            'email' => 'required|email',
        ],[
            'email.required' => __('apiValid.email_required'),
            'email.email' => __('apiValid.email_email'),
        ]);

        if($validator->fails()){
            return $this->sendError($validator->messages()->all(),410);
        }

        $worker = Worker::query()->where('email',$request->email)->first();
        if (!$worker) {
            return $this->sendError(__('api.user_email_not_found'));
        }
        $code = rand(1000, 9999);
        $worker->update([
            'forget_status' => 1,
            'forget_code' => $code
        ]);
        Mail::to($worker->email)->send(new ForgetPasswordMail(['name' => $worker->username, 'code' => $code]));
        return $this->sendResponse(new UserResource($worker),__('api.user_found_email_success'));
    }

    public function checkCode(Request $request){
        $data = $request->all();
        $validator = Validator::make($data,[
            'worker_id' => 'required|exists:workers,id',
            'code' => 'required|numeric',
        ],[
            'worker_id.required' => __('apiValid.user_id_required'),
            'worker_id.exists' => __('apiValid.user_id_exists'),
            'code.required' => __('apiValid.code_required'),
            'code.numeric' => __('apiValid.code_numeric'),
        ]);

        if($validator->fails()){
            return $this->sendError($validator->messages()->all(),410);
        }

        $worker = Worker::query()->find($request->worker_id);
        if($worker->forget_code == $request->code){
            return $this->sendResponse(new UserResource($worker),__('api.user_code_success'));
        }else{
            return $this->sendError(__('api.user_code_fail'));
        }
    }

    public function changePassword(Request $request){

        $data = $request->all();
        $validator = Validator::make($data,[
            'worker_id' => 'required|exists:workers,id',
            'password' => 'required|confirmed',
            'password_confirmation' => 'required'
        ],[
            'worker_id.required' => __('apiValid.user_id_required'),
            'worker_id.exists' => __('apiValid.user_id_exists'),
            'password.required' =>__('apiValid.password_required'),
            'password_confirmation.required' => __('apiValid.password_required'),
            'password.confirmed' => __('validation.confirmed'),
        ]);


        if($validator->fails()){
            return $this->sendError($validator->messages()->all(),410);
        }
        $worker = Worker::query()->find($request->worker_id);

        $worker->update([
            'password' => Hash::make($request->password),
            'forget_status' => 0,
            'forget_code' => null
        ]);

        return $this->sendResponse([],__('api.user_forget_password_success'));
    }
}
