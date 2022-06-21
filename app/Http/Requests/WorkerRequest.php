<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WorkerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'username' => 'required',
            'password' => 'required_without:id',
            'city_id' => 'required',
            'subCategory_id' => 'required',
            'phone' =>'required|unique:users,phone,' . $this->id,
            'email' => 'required|email|unique:users,email,' . $this->id,
            'image' => 'required_without:id|image|mimes:jpg,png,jpeg,gif,svg',
        ];
    }

    public function messages()
    {
        return [
            'email.required' => __('validation.required'),
            'email.email' => __('validation.email'),
            'email.unique' => __('validation.unique'),
            'password.required_without' => __('validation.required_without'),
            'username.required' => __('validation.required'),
            'city_id.required' => __('validation.required'),
            'subCategory_id.required' => __('validation.required'),
            'phone.required' => __('validation.required'),
            'phone.unique' => __('validation.unique'),
            'image.required_without' => __('validation.required_without'),
            'image.image' => __('validation.image'),
            'image.mimes' => __('validation.mimes'),
        ];
    }
}
