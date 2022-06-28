<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Mail\ForgetPasswordMail;
use App\Mail\VerificationEmail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ForgetPasswordController extends Controller
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

        $user = User::query()->where('email',$request->email)->first();
        if (!$user) {
            return $this->sendError(__('api.user_email_not_found'));
        }
        $code = rand(1000, 9999);
        $user->update([
            'forget_status' => 1,
            'forget_code' => $code
        ]);
        Mail::to($user->email)->send(new ForgetPasswordMail(['name' => $user->username, 'code' => $code]));
        return $this->sendResponse(new UserResource($user),__('api.user_found_email_success'));
    }

    public function checkCode(Request $request){
        $data = $request->all();
        $validator = Validator::make($data,[
            'user_id' => 'required|exists:users,id',
            'code' => 'required|numeric',
        ],[
            'user_id.required' => __('apiValid.user_id_required'),
            'user_id.exists' => __('apiValid.user_id_exists'),
            'code.required' => __('apiValid.code_required'),
            'code.numeric' => __('apiValid.code_numeric'),
        ]);

        if($validator->fails()){
            return $this->sendError($validator->messages()->all(),410);
        }

        $user = User::query()->find($request->user_id);
        if($user->forget_code == $request->code){
            return $this->sendResponse(new UserResource($user),__('api.user_code_success'));
        }else{
            return $this->sendError(__('api.user_code_fail'));
        }
    }

    public function changePassword(Request $request){

        $data = $request->all();
        $validator = Validator::make($data,[
            'user_id' => 'required|exists:users,id',
            'password' => 'required|confirmed',
            'password_confirmation' => 'required'
        ],[
            'user_id.required' => __('apiValid.user_id_required'),
            'user_id.exists' => __('apiValid.user_id_exists'),
            'password.required' =>__('apiValid.password_required'),
            'password_confirmation.required' => __('apiValid.password_required'),
            'password.confirmed' => __('validation.confirmed'),
        ]);

        if($validator->fails()){
            return $this->sendError($validator->messages()->all(),410);
        }
        $user = User::query()->find($request->user_id);

        $user->update([
            'password' => Hash::make($request->password),
            'forget_status' => 0,
            'forget_code' => null
        ]);

        return $this->sendResponse([],__('api.user_forget_password_success'));
    }


}
