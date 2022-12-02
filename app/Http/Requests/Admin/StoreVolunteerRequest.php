<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreVolunteerRequest extends FormRequest
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
            // Account information
            'email' => 'required|email|unique:volunteers,email',
            'password' => 'required|min:6',

            // Personal information
            'national_id'     => 'required|integer',
            'fname_ar'        => 'required|min:3',
            'sname_ar'        => 'required|min:3',
            'tname_ar'        => 'required|min:3',
            'lname_ar'        => 'required|min:3',
            'gender'          => 'required|in:0,1',
            'mobile'          => 'required',
            'mobile_key'      => 'required',
            'country'         => 'required',
            'branch'          => 'required',
            'nationality'     => 'required',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json($validator->errors(), 400));
    }
}
