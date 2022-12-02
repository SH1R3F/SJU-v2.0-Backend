<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateMemberRequest extends FormRequest
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
            'source'               => 'required',
            'date'                 => 'required|date',
            'qualification'        => 'required',
            'major'                => 'required',
            'journalist_job_title' => 'required',
            'journalist_employer'  => 'required',
            'newspaper_type'       => 'required|integer|in:1,2',
            'job_title'            => 'required',
            'employer'             => 'required',
            'worktel'              => 'required|integer',
            'worktel_ext'          => 'required|integer',
            'fax'                  => 'required|integer',
            'fax_ext'              => 'required|integer',
            'post_box'             => 'required|integer',
            'post_code'            => 'required|integer',
            'city'                 => 'required',
            'email'                => [
                'required',
                'email',
                Rule::unique('members')->ignore(Auth::guard('api-members')->user()->id)
            ],
            'delivery_method'      => 'sometimes|nullable',
            'delivery_address'     => 'sometimes|nullable',
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
