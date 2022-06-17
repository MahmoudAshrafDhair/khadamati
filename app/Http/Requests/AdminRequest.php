<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminRequest extends FormRequest
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
            'name' => 'required',
            'email' => 'required|email|unique:admins,email,'.$this->id,
            'password' => 'required_without:id',
            'image' => 'required_without:id',
            'roles_name' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => __('validation.required'),
            'email.required' => __('validation.required'),
            'email.email' => __('validation.email'),
            'email.unique' => __('validation.unique'),
            'password.required_without' => __('validation.required_without'),
            'image.required_without' => __('validation.required_without'),
//            'image.image' => __('validation.image'),
//            'image.mimes' => __('validation.mimes'),
            'roles_name.required' => __('validation.mimes'),
        ];
    }
}
