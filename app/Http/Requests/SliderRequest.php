<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SliderRequest extends FormRequest
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
            'image' => 'required_without:id|image|mimes:jpg,png,jpeg,gif,svg',
        ];
    }

    public function messages()
    {
        return [
            'image.required_without' => __('validation.required_without'),
            'image.image' => __('validation.image'),
            'image.mimes' => __('validation.mimes'),
        ];
    }
}
