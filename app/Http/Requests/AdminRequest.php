<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'email'     => [
                'required',
                'email',
                'unique:admins,email',
                Rule::when(request()->isMethod('POST'), Rule::unique('admins')),
                Rule::when(request()->isMethod('PUT'), Rule::unique('admins')->ignore($this->admin)),
            ],
            'password'  => 'required|min:6',
            'username'  => 'required|min:3|unique:admins,username',
            'mobile'    => 'required',
            'role'      => 'required|exists:roles,id',
            'branch_id' => 'present'
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
