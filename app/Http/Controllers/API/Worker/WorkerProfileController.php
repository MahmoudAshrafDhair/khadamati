<?php

namespace App\Http\Controllers\API\Worker;

use App\Http\Controllers\Controller;
use App\Http\Resources\AppointmentResource;
use App\Http\Resources\DayResource;
use App\Http\Resources\WorkerProfileResource;
use App\Models\Appointment;
use App\Models\Day;
use App\Models\Worker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class WorkerProfileController extends Controller
{

    public function getDays(){
        $days = Day::query()->get();
        return $this->sendResponse(DayResource::collection($days),"");
    }

    public function addAppointments(Request $request){
        $data = $request->all();
        $validator = Validator::make($data,[
            'day_id' => 'required',
            'start' => 'required',
            'end' => 'required',
        ],[
            'day_id.required' => __('apiValid.day_id_required'),
            'start.required' => __('apiValid.start_required'),
            'end.required' => __('apiValid.end_required'),
        ]);

        if ($validator->fails()){
            return $this->sendError($validator->messages()->all(),410);
        }
        $data['worker_id'] = Auth::guard('worker-api')->user()->id;

        Appointment::query()->create($data);
        return $this->sendResponse([],__('api.add_appointment_success'));
    }

    public function updateAppointments(Request $request){
        $data = $request->all();
        $validator = Validator::make($data,[
            'appointment_id' => 'required',
            'day_id' => 'required',
            'start' => 'required',
            'end' => 'required',
        ],[
            'appointment_id.required' => __('apiValid.appointment_id_required'),
            'day_id.required' => __('apiValid.day_id_required'),
            'start.required' => __('apiValid.start_required'),
            'end.required' => __('apiValid.end_required'),
        ]);

        if ($validator->fails()){
            return $this->sendError($validator->messages()->all(),410);
        }
        $worker_id = Auth::guard('worker-api')->user()->id;
        $appointment = Appointment::query()->where('worker_id',$worker_id)->where('day_id',$request->day_id)->first();
        if($appointment){
            return $this->sendError(__('api.appointment_use'));
        }
        $appointment = Appointment::query()->find($request->appointment_id);
        $appointment->update($data);
        return $this->sendResponse([],__('api.update_appointment_success'));
    }

    public function getAppointments(){
        $worker_id = Auth::guard('worker-api')->user()->id;
        $worker = Worker::query()->with(['appointments'])->find($worker_id);
        return $this->sendResponse(AppointmentResource::collection($worker->appointments),"");
    }

    public function getProfile(){
        $worker_id = Auth::guard('worker-api')->user()->id;
        $worker = Worker::query()->with(['appointments','city'])->find($worker_id);
        return $this->sendResponse(new WorkerProfileResource($worker),"");
    }

    public function updateProfile(Request $request){
        $worker_id = Auth::guard('worker-api')->user()->id;
        $worker = Worker::query()->with(['appointments','city'])->find($worker_id);
        $data = $request->all();
        $validator = Validator::make($data,[
            'username' => 'required',
            'email' => 'required|email|unique:users,email,'.$worker_id,
            'phone' => 'required|regex:/(05)[0-9]{8}/|unique:users,phone,'.$worker_id,
            'city_id' => 'required|numeric',
            'subCategory_id' => 'required|numeric',
        ],[
            'username.required' => __('apiValid.username_required'),
            'email.required' => __('apiValid.email_required'),
            'email.email' => __('apiValid.email_email'),
            'email.unique' => __('apiValid.email_unique'),
            'phone.required' => __('apiValid.phone_required'),
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

        if ($request->hasFile('image')) {
            if($worker->image != null){
                Storage::disk('public')->delete($worker->image);
                $data['image'] = uploadImage($request->file('image'),'workers');
            }else{
                $data['image'] = uploadImage($request->file('image'),'workers');
            }
        }

        $worker->update($data);
        return $this->sendResponse([],__('api.worker_updated_success'));
    }

    public function updatePassword(Request $request){
        $worker_id = Auth::guard('worker-api')->user()->id;
        $worker = Worker::query()->with(['appointments','city'])->find($worker_id);
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

        if (! Hash::check($request->old_password, $worker->password)) {
            return $this->sendError(__('api.check_old_password'));
        }

        if($validator->fails()){
            return $this->sendError($validator->messages()->all(),410);
        }

        $worker->update([
            'password' => Hash::make($request->password)
        ]);

        return $this->sendResponse([],__('api.update_password_success'));
    }
}
