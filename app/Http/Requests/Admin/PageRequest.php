<?php

namespace App\Http\Requests\Admin;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class PageRequest extends FormRequest
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
            'title_ar'   => 'required',
            'title_en'   => 'required',
            'slug'       => [
                'required',
                'alpha_dash',
                Rule::when(request()->isMethod('POST'), Rule::unique('pages')),
                Rule::when(request()->isMethod('PUT'), Rule::unique('pages')->ignore($this->page)),
            ],
            'content_ar' => 'required',
            'content_en' => 'required'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json($validator->errors(), 400));
    }
}
