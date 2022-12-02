<?php

namespace App\Http\Requests\Admin;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateSubscriberRequest extends FormRequest
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
            'subscriberEmail' => [
                'nullable',
                'email',
                Rule::unique('subscribers', 'email')
            ],
            'password' => 'nullable|min:6|confirmed',

            // Personal information
            'fname_ar'        => 'nullable|min:3',
            'sname_ar'        => 'nullable|min:3',
            'tname_ar'        => 'nullable|min:3',
            'lname_ar'        => 'nullable|min:3',
            'fname_en'        => 'nullable|min:3',
            'sname_en'        => 'nullable|min:3',
            'tname_en'        => 'nullable|min:3',
            'lname_en'        => 'nullable|min:3',
            'gender'          => 'nullable|in:0,1',
            'birthday_meladi' => 'nullable|date',
            'birthday_hijri'  => 'nullable|date',
            'qualification'   => 'nullable|min:3',
            'major'           => 'nullable|min:3',
            'job_title'       => 'nullable|min:3',
            'employer'        => 'nullable|min:3',
            'country'         => 'nullable',
            'city'            => 'nullable',
            'nationality'     => 'nullable',
            'post_box'        => 'nullable|min:3',
            'post_code'       => 'nullable|min:3',

            // Contact information
            'worktel'         => 'nullable',
            'worktel_ext'     => 'nullable',
            'fax'             => 'nullable',
            'fax_ext'         => 'nullable',
            'mobile'          => 'nullable',
            'mobile_key'      => 'nullable',
        ];
    }



    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json($validator->errors(), 400));
    }
}
