<?php

namespace App\Http\Requests\Admin\Course;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class QuestionRequest extends FormRequest
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
            'question' => 'required|min:3',
            'type'     => 'sometimes',
            'answer1'  => 'required_if:type,true',
            'color1'   => 'required_if:type,true',
            'answer2'  => 'required_if:type,true',
            'color2'   => 'required_if:type,true',
            'answer3'  => 'required_if:type,true',
            'color3'   => 'required_if:type,true',
            'answer4'  => 'required_if:type,true',
            'color4'   => 'required_if:type,true',
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
