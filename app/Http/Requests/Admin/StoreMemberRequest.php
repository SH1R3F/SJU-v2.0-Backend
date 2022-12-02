<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreMemberRequest extends FormRequest
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
            // Personal information
            'national_id'          => 'required|min:3|unique:members,national_id',
            'mobile'               => 'required|unique:members,national_id',
            'fname_ar'             => 'required|min:3',
            'sname_ar'             => 'required|min:3',
            'tname_ar'             => 'required|min:3',
            'lname_ar'             => 'required|min:3',
            'fname_en'             => 'required|min:3',
            'sname_en'             => 'required|min:3',
            'tname_en'             => 'required|min:3',
            'lname_en'             => 'required|min:3',
            'gender'               => 'required|in:0,1',
            'birthday_meladi'      => 'required|date',
            'birthday_hijri'       => 'required|date',
            'nationality'          => 'required|integer',
            'qualification'        => 'required|min:3',
            'major'                => 'required|min:3',
            'journalist_job_title' => 'required|min:3',
            'journalist_employer'  => 'required|min:3',
            'newspaper_type'       => 'required|integer',
            'job_title'            => 'required|min:3',
            'employer'             => 'required|min:3',
            'worktel'              => 'required',
            'worktel_ext'          => 'required',
            'fax'                  => 'required',
            'fax_ext'              => 'required',
            'post_box'             => 'required|min:3',
            'post_code'            => 'required|min:3',
            'city'                 => 'required|integer',
            'memberEmail'          => 'required|email|unique:members,email',
            'password'             => 'required|min:6',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json($validator->errors(), 400));
    }
}
