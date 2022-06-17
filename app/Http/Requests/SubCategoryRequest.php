<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubCategoryRequest extends FormRequest
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
            'name_ar' => 'required',
            'name_en' => 'required',
            'category_id' => 'required',
            'image' => 'required_without:id|image|mimes:jpg,png,jpeg,gif,svg',
        ];
    }

    public function messages()
    {
        return [
            'name_ar.required' => __('validation.required'),
            'name_en.required' => __('validation.required'),
            'category_id.required' => __('validation.required'),
            'image.required_without' => __('validation.required_without'),
            'image.image' => __('validation.image'),
            'image.mimes' => __('validation.mimes'),
        ];
    }
}
