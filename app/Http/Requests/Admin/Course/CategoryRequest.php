<?php

namespace App\Http\Requests\Admin\Course;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class CategoryRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name_ar' => [
                'required',
                'min:3',
                Rule::when(request()->isMethod('POST'), Rule::unique('categories')),
                Rule::when(request()->isMethod('PUT'), Rule::unique('categories')->ignore($this->category)),
            ],
            'name_en'        => 'nullable|min:3',
            'description_ar' => 'nullable|min:3',
            'description_en' => 'nullable|min:3'
        ];
    }


    /**
     * Get the error messages for the defined validation rules.*
     * @return array
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json($validator->errors(), 400));
    }
}
